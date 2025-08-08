<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Job;
use App\Models\JobActivity;

class DirectorDashboardController extends Controller
{
    public function index()
    {
        // Statistik utama
        $totalProjects = Project::count();
        $activeProjects = Project::whereIn('status', ['active', 'in_progress'])->count();
        $completedProjects = Project::where('status', 'completed')->count();
        $totalJobs = Job::count();

        // Jobs completed this month (status 'selesai' atau 'completed')
        $jobsCompletedThisMonth = Job::whereIn('status', ['completed', 'selesai'])
            ->whereMonth('updated_at', now()->month)
            ->whereYear('updated_at', now()->year)
            ->count();

        // Data untuk bar chart (6 bulan terakhir)
        $months = [];
        $jobsCompletedPerMonth = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $months[] = $month->format('M');
            $jobsCompletedPerMonth[] = Job::whereIn('status', ['completed', 'selesai'])
                ->whereMonth('updated_at', $month->month)
                ->whereYear('updated_at', $month->year)
                ->count();
        }

        // Data status job (pakai label & warna dari model)
        $statuses = ['buat_baru', 'pengerjaan', 'tunda', 'tes', 'perbaikan', 'selesai'];
        $jobsStatusLabels = [];
        $jobsStatusData = [];
        $jobsStatusColors = [];

        foreach ($statuses as $status) {
            $jobTemp = new Job(['status' => $status]);
            $jobsStatusLabels[] = $jobTemp->status_label;       // bahasa Inggris
            $jobsStatusColors[] = $jobTemp->status_badge['bg']; // warna HEX
            $jobsStatusData[] = Job::where('status', $status)->count();
        }

        // Recent Activities → ambil dari JobActivity
        $recentActivities = JobActivity::with(['job', 'user'])
            ->latest('activity_date')
            ->take(5)
            ->get();

        return view('dashboard.director', compact(
            'totalProjects',
            'activeProjects',
            'completedProjects',
            'totalJobs',
            'jobsCompletedThisMonth',
            'months',
            'jobsCompletedPerMonth',
            'jobsStatusLabels',
            'jobsStatusData',
            'jobsStatusColors',
            'recentActivities'
        ));
    }
}
