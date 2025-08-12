<?php

namespace App\Http\Controllers;

use App\Models\JobActivity;
use App\Models\Job;
use App\Models\User;
use Illuminate\Http\Request;

class JobActivityController extends Controller
{
    public function index(Request $request)
    {
        $query = JobActivity::with([
            'job.project',
            'user'
        ]);

        // Filter by search (activity_note)
        if ($request->filled('search')) {
            $query->where('activity_note', 'like', '%' . $request->search . '%');
        }
        // Filter by project
        if ($request->filled('project')) {
            $query->whereHas('job', function($q) use ($request) {
                $q->where('project_id', $request->project);
            });
        }
        // Filter by operator
        if ($request->filled('operator')) {
            $query->where('user_id', $request->operator);
        }
        // Filter by date
        if ($request->filled('date')) {
            $query->whereDate('activity_date', $request->date);
        }

        $activities = $query->latest()->get();

        $projects = \App\Models\Project::orderBy('name')->get();
        $operators = \App\Models\User::where('role', 'operator')->orderBy('name')->get();

        return view('activities.index', compact('activities', 'projects', 'operators'));
    }

    public function create()
    {
        $jobs = Job::all();
        $users = User::all();
        return view('activities.create', compact('jobs', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'job_id' => 'required|exists:jobs,id',
            'user_id' => 'required|exists:users,id',
            'status' => 'required|in:buat_baru,pengerjaan,tunda,tes,perbaikan,selesai',
            'activity_note' => 'nullable|string',
            'activity_date' => 'required|date',
        ]);

        JobActivity::create($validated);

        return redirect()->route('activities.index')->with('success', 'Time Log has been added successfully.');
    }

    public function show(int $id)
    {
        $activity = JobActivity::with(['job', 'user'])->findOrFail($id);
        return view('activities.show', compact('activity')); 
    }

    public function edit(int $id)
    {
        $activity = JobActivity::findOrFail($id);
        $jobs = Job::all();
        $users = User::all();
        return view('activities.edit', compact('activity', 'jobs', 'users'));
    }

    public function update(Request $request, int $id)
    {
        $activity = JobActivity::findOrFail($id);

        $validated = $request->validate([
            'job_id' => 'required|exists:jobs,id',
            'user_id' => 'required|exists:users,id',
            'status' => 'required|in:buat_baru,pengerjaan,tunda,tes,perbaikan,selesai',
            'activity_note' => 'nullable|string',
            'activity_date' => 'required|date',
        ]);

        $activity->update($validated);

        return redirect()->route('activities.index')->with('success', 'Time Log has been updated successfully.');
    }

    public function destroy(int $id)
    {
        $activity = JobActivity::findOrFail($id);
        $activity->delete();

        return redirect()->route('activities.index')->with('success', 'Time Log has been deleted successfully.');
    }
}
