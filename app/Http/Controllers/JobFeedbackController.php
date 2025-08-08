<?php

namespace App\Http\Controllers;

use App\Models\JobFeedback;
use App\Models\JobActivity;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobFeedbackController extends Controller
{
    // Menampilkan semua feedback (fitur admin, bisa digabung ke view)
    public function index()
    {
        $feedbacks = JobFeedback::with(['activity', 'job', 'user'])->get();
        return view('feedbacks.index', compact('feedbacks'));
    }

    // FORM TAMBAH Feedback berdasarkan JOB (khusus Operator & status 'tes')
    public function create($jobId)
    {
        $job = Job::findOrFail($jobId);

        // Ubah pengecekan role ke lowercase
        if (Auth::user()->role !== 'operator' || $job->status !== 'tes') {
            abort(403, 'Tidak diizinkan.');
        }

        return view('feedbacks.create_operator', compact('job'));
    }

    // SIMPAN feedback untuk JOB (khusus Operator)
    public function store(Request $request, $jobId)
    {
        $job = Job::findOrFail($jobId);

        if (Auth::user()->role !== 'operator' || $job->status !== 'tes') {
            abort(403, 'Tidak diizinkan.');
        }

        $validated = $request->validate([
            'feedback' => 'required|string'
        ]);

        JobFeedback::create([
            'job_id'         => $job->id,
            'user_id'        => Auth::id(),
            'feedback_note'  => $validated['feedback'],
            'feedback_date'  => now(),
        ]);

        return redirect()->route('jobs.show', $job->id)->with('success', 'Feedback berhasil dikirim.');
    }

    // FORM TAMBAH feedback dari aktivitas (fitur admin atau pengguna tertentu)
    public function createActivityFeedback()
    {
        $activities = JobActivity::all();
        return view('feedbacks.create', compact('activities'));
    }

    // SIMPAN feedback berdasarkan aktivitas
    public function storeActivityFeedback(Request $request)
    {
        $validated = $request->validate([
            'job_activity_id' => 'required|exists:job_activities,id',
            'feedback'        => 'required|string'
        ]);

        JobFeedback::create($validated);

        return redirect()->route('feedbacks.index')->with('success', 'Feedback berhasil ditambahkan.');
    }

    public function edit(string $id)
    {
        $feedback = JobFeedback::findOrFail($id);
        $activities = JobActivity::all();
        return view('feedbacks.edit', compact('feedback', 'activities'));
    }

    public function update(Request $request, string $id)
    {
        $feedback = JobFeedback::findOrFail($id);

        $validated = $request->validate([
            'job_activity_id' => 'required|exists:job_activities,id',
            'feedback'        => 'required|string'
        ]);

        $feedback->update($validated);

        return redirect()->route('feedbacks.index')->with('success', 'Feedback berhasil diperbarui');
    }

    public function destroy(string $id)
    {
        $feedback = JobFeedback::findOrFail($id);
        $feedback->delete();

        return redirect()->route('feedbacks.index')->with('success', 'Feedback berhasil dihapus');
    }
}
