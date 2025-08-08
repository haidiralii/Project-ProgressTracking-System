<?php

namespace App\Http\Controllers;

use App\Models\JobAssignment;
use App\Models\Job;
use App\Models\User;
use Illuminate\Http\Request;

class JobAssignmentController extends Controller
{
    public function index()
    {
        $assignments = JobAssignment::with('job', 'user')->get();
        return view('assignments.index', compact('assignments'));
    }

    public function create()
    {
        $jobs = Job::all();
        $users = User::all();
        return view('assignments.create', compact('jobs', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'job_id' => 'required|exists:jobs,id',
            'user_id' => 'required|exists:users,id',
        ]);

        JobAssignment::create($validated);

        return redirect()->route('assignments.index')->with('success', 'Assignment has been added successfully.');
    }

    public function edit(string $id)
    {
        $assignment = JobAssignment::findOrFail($id);
        $jobs = Job::all();
        $users = User::all();
        return view('assignments.edit', compact('assignment', 'jobs', 'users'));
    }

    public function update(Request $request, string $id)
    {
        $assignment = JobAssignment::findOrFail($id);

        $validated = $request->validate([
            'job_id' => 'required|exists:jobs,id',
            'user_id' => 'required|exists:users,id',
        ]);

        $assignment->update($validated);

        return redirect()->route('assignments.index')->with('success', 'Assignment has been updated successfully.');
    }

    public function destroy(string $id)
    {
        $assignment = JobAssignment::findOrFail($id);
        $assignment->delete();

        return redirect()->route('assignments.index')->with('success', 'Assignment has been deleted successfully.');
    }
}
