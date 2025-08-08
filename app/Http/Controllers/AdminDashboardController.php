<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Job;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AdminDashboardController extends Controller
{
    /**
     * Redirect dashboard berdasarkan role user.
     */
    public function redirectDashboard()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            return $this->index();
        }

        // Operator dashboard
        return $this->operatorDashboard();
    }

    /**
     * Tampilkan dashboard untuk operator (programmer & tester)
     */
    public function operatorDashboard()
    {
        $user = Auth::user();

        // Jobs yang di-assign ke operator
        $assignedJobs = $user->jobs()->with('project')->get();

        // Jobs yang sudah selesai oleh operator
        $completedJobs = $assignedJobs->where('status', 'selesai');

        // Jobs yang statusnya "test" dan di-assign ke operator (untuk tester)
        $testJobs = $assignedJobs->where('status', 'test');

        return view('dashboard.operator', compact('assignedJobs', 'completedJobs', 'testJobs'));
    }

    /**
     * Tampilkan halaman dashboard admin.
     */
    public function index()
    {
        $totalProjects = Project::count();
        $totalJobs = Job::count();
        $totalUsers = User::where('role', 'operator')->count();

        $completedJobs = Job::where('status', 'selesai')->count();
        $onGoingJobs = Job::where('status', 'proses')->count();
        $delayedJobs = Job::where('deadline', '<', now())->where('status', '!=', 'selesai')->count();

        $completedPercentage = $totalJobs > 0 ? round(($completedJobs / $totalJobs) * 100) : 0;

        // Ambil semua proyek untuk tampilan ringkasan
        $projects = Project::with('jobs')->orderBy('created_at', 'desc')->get();

        // Tugas yang dibuat hari ini
        $todayTasks = Job::whereDate('created_at', now())->get();

        return view('dashboard.admin', compact(
            'totalProjects',
            'totalJobs',
            'totalUsers',
            'completedJobs',
            'onGoingJobs',
            'delayedJobs',
            'completedPercentage',
            'projects',
            'todayTasks'
        ));
    }
}
