<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    /**
     * Menampilkan detail proyek.
     */
    public function show(Project $project)
    {
        // Eager load jobs and their assigned operators for detail view
        $project->load(['jobs.operators']);
        return view('projects.show', compact('project'));
    }

    /**
     * Menampilkan daftar proyek.
     */
    public function index(Request $request)
    {
        $query = Project::query();

        // Filter status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter starred
        if ($request->filled('starred') && $request->starred == '1') {
            $query->where('is_starred', true);
        }

        $projects = $query->with('jobs')->orderByDesc('created_at')->get();

        // Hitung progress untuk setiap project
        foreach ($projects as $project) {
            $totalJobs = $project->jobs->count();
            $completedJobs = $project->jobs->where('status', 'selesai')->count();
            $project->progress_percentage = $totalJobs > 0
                ? intval(round(($completedJobs / $totalJobs) * 100))
                : 0;
        }

        // Jika role Director → tampilkan view khusus
        if (auth()->user()->role === 'director') {
            return view('projects.index_director', compact('projects'));
        }

        // Default → Admin & Operator
        return view('projects.index', compact('projects'));
    }

    /**
     * Menampilkan form untuk membuat proyek baru.
     */
    public function create()
    {
        return view('projects.create');
    }

    /**
     * Menyimpan proyek baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date'  => 'required|date',
            'end_date'    => 'nullable|date|after_or_equal:start_date',
            'status'      => 'required|string|in:active,in_progress,completed',

        ]);

        Project::create($validated);

        return redirect()->route('projects.index')->with('success', 'Project created successfully!');
    }

    /**
     * Menampilkan form edit proyek.
     */
    public function edit(Project $project)
    {
        return view('projects.edit', compact('project'));
    }

    /**
     * Memperbarui data proyek.
     */
    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'required|string',
            'start_date'  => 'required|date',
            'end_date'    => 'nullable|date|after_or_equal:start_date',
            'status'      => 'required|string|in:active,in_progress,completed',
        ]);

        try {
            $project->update($validated);
            return redirect()->route('projects.index')->with('success', 'Project has been updated successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Gagal memperbarui project: ' . $e->getMessage()]);
        }
    }

    /**
     * Menghapus proyek.
     */
    public function destroy(Project $project)
    {
        try {
            $project->delete();
            return redirect()->route('projects.index')->with('success', 'Project has been deleted successfully.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal menghapus project: ' . $e->getMessage()]);
        }
    }

    /**
     * Toggle starred status of the project (AJAX).
     */
    public function toggleStar(Project $project)
    {
        $project->is_starred = !$project->is_starred;
        $project->save();

        return response()->json(['is_starred' => $project->is_starred]);
    }
}
