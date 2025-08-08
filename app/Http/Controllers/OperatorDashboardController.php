<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OperatorDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Jobs yang di-assign ke operator
        $assignedJobs = $user->assignedJobs()->with('project')->get();

        // Jobs yang sudah selesai oleh operator
        $completedJobs = $assignedJobs->where('status', 'selesai');

        // Jobs yang statusnya "tes" (bukan "test") dan di-assign ke operator
        $testJobs = $assignedJobs->where('status', 'tes');

        // Jobs yang deadline hari ini
        $todayJobs = $assignedJobs->filter(function($job) {
            return $job->deadline && \Carbon\Carbon::parse($job->deadline)->isToday();
        });

        return view('dashboard.operator', compact(
            'assignedJobs',
            'completedJobs',
            'testJobs',
            'todayJobs'
        ));
    }
}
