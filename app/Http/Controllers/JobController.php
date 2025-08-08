<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Project;
use App\Models\User;
use App\Models\JobActivity;
use Illuminate\Http\Request;

class JobController extends Controller
{
    /**
     * Semua status yang valid sesuai enum di database.
     */
    private const JOB_STATUSES = ['buat_baru', 'pengerjaan', 'tunda', 'tes', 'perbaikan', 'selesai'];

    // Show all jobs (with filters)
    public function index(Request $request)
    {
        $query = Job::with(['project', 'operators']);

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('project')) {
            $query->where('project_id', $request->project);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('deadline')) {
            $query->whereDate('deadline', $request->deadline);
        }

        $jobs = $query->latest()->get();
        $projects = Project::all();

        // Tambahkan status badge
        foreach ($jobs as $job) {
            $job->statusBadge = self::getJobStatusBadge($job->status);
        }

        return view('jobs.index', compact('jobs', 'projects'));
    }

    // Form to create a new job
    public function create(Request $request)
    {
        $projects = Project::all();
        $operators = User::where('role', 'operator')->get();
        $project = $request->project_id ? Project::find($request->project_id) : null;

        return view('jobs.create', compact('project', 'projects', 'operators'));
    }

    // Store new job
    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id'   => 'required|exists:projects,id',
            'title'        => 'required|string|max:255',
            'description'  => 'nullable|string',
            'requested_at' => 'required|date',
            'deadline'     => 'nullable|date|after_or_equal:requested_at',
            'status'       => 'required|in:' . implode(',', self::JOB_STATUSES),
        ]);

        $job = Job::create($validated);
        $job->operators()->sync($request->input('operator_ids', []));

        return redirect()->route('jobs.index')->with('success', 'Job has been created successfully.');
    }

    // Show job detail
    public function show(Job $job)
    {
        $job->load(['project', 'operators', 'activities', 'feedbacks']);
        $job->statusBadge = self::getJobStatusBadge($job->status);
        return view('jobs.show', compact('job'));
    }

    // Form to edit job
    public function edit(Job $job)
    {
        $user = auth()->user();

        if ($user->role === 'admin') {
            $projects = Project::all();
            $operators = User::where('role', 'operator')->get();
            $job->load('project', 'operators');
            return view('jobs.edit', compact('job', 'projects', 'operators'));
        }

        // Jika operator, redirect ke update_operator jika dia terlibat
        if ($user->role === 'operator' && $job->operators->contains($user->id)) {
            return redirect()->route('jobs.update_operator', $job->id);
        }

        abort(403, 'Unauthorized access.');
    }

    // Update job
    public function update(Request $request, Job $job)
    {
        if (auth()->user()->role === 'operator') {
            $validated = $request->validate([
                'status'        => 'required|in:' . implode(',', self::JOB_STATUSES),
                'activity_date' => 'nullable|date',
                'activity_note' => 'nullable|string',
            ]);

            $job->update(['status' => $validated['status']]);

            if (!empty($validated['activity_date']) || !empty($validated['activity_note'])) {
                JobActivity::create([
                    'job_id'        => $job->id,
                    'user_id'       => auth()->id(),
                    'status'        => $validated['status'],
                    'activity_date' => $validated['activity_date'] ?? now(),
                    'activity_note' => $validated['activity_note'] ?? '',
                ]);
            }

            return redirect()->route('jobs.edit', $job->id)->with('success', 'Status and activity updated.');
        }

        // Admin update
        $validated = $request->validate([
            'project_id'   => 'required|exists:projects,id',
            'title'        => 'required|string|max:255',
            'description'  => 'nullable|string',
            'requested_at' => 'required|date',
            'deadline'     => 'nullable|date|after_or_equal:requested_at',
            'status'       => 'required|in:' . implode(',', self::JOB_STATUSES),
        ]);

        $job->update($validated);
        $job->operators()->sync($request->input('operator_ids', []));

        return redirect()->route('jobs.index')->with('success', 'Job has been updated successfully.');
    }

    // Mark job as completed
    public function complete(Job $job)
    {
        if ($job->status !== 'selesai') {
            $job->update(['status' => 'selesai']);
        }

        return redirect()->route('jobs.index')->with('success', 'Job marked as completed.');
    }

    // Delete job
    public function destroy(Job $job)
    {
        $job->delete();
        return redirect()->route('jobs.index')->with('success', 'Job deleted successfully.');
    }

    // Form khusus operator untuk update status & activity
    public function updateOperatorForm(Job $job)
    {
        $user = auth()->user();
        if ($user->role !== 'operator' || !$job->operators->contains($user->id)) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
        }

        $job->load('project', 'operators');
        $job->statusBadge = self::getJobStatusBadge($job->status);
        return view('jobs.update_operator', compact('job'));
    }

    // Proses update khusus operator
    public function updateOperator(Request $request, Job $job)
    {
        $user = auth()->user();
        if ($user->role !== 'operator' || !$job->operators->contains($user->id)) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
        }

        $validated = $request->validate([
            'status'        => 'required|in:pengerjaan,tunda,tes,perbaikan,selesai',
            'activity_note' => 'nullable|string',
            'activity_date' => 'required|date',
        ]);

        $job->update(['status' => $validated['status']]);

        JobActivity::create([
            'job_id'        => $job->id,
            'user_id'       => $user->id,
            'status'        => $validated['status'],
            'activity_date' => $validated['activity_date'],
            'activity_note' => $validated['activity_note'] ?? '',
        ]);

        return redirect()->route('jobs.index')->with('success', 'Job updated and activity logged successfully.');
    }

    // Get label + class for job status
    public static function getJobStatusBadge($status)
    {
        $map = [
            'buat_baru'  => ['label' => 'New',        'bg' => '#E0E7FF', 'text' => '#3730A3'],
            'pengerjaan' => ['label' => 'In Progress','bg' => '#FEF9C3', 'text' => '#92400E'],
            'tunda'      => ['label' => 'Pending',    'bg' => '#FDE68A', 'text' => '#B45309'],
            'tes'        => ['label' => 'Testing',    'bg' => '#E0F2FE', 'text' => '#0369A1'],
            'perbaikan'  => ['label' => 'Revision',   'bg' => '#FDE2E1', 'text' => '#B91C1C'],
            'selesai'    => ['label' => 'Completed',  'bg' => '#DCFCE7', 'text' => '#15803D'],
        ];
        return $map[$status] ?? [
            'label' => ucfirst($status),
            'bg'    => '#F3F4F6',
            'text'  => '#374151',
        ];
    }
}
