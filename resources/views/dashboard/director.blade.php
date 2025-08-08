@extends('layouts.admin')

@section('title', 'ManPro - Director Dashboard')

@section('content')
<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(24px);}
        to { opacity: 1; transform: translateY(0);}
    }
    .animate-fade-in-up {
        animation: fadeInUp 0.6s cubic-bezier(0.4,0,0.2,1) forwards;
        opacity: 0;
    }
    body, .dashboard-bg {
        background-color: #F8F0F0;
    }
    .dashboard-card {
        background: #fff;
        border-radius: 1rem;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        padding: 2rem 1.5rem;
        transition: box-shadow 0.2s, transform 0.2s;
        text-align: center;
    }
    .dashboard-card:hover {
        box-shadow: 0 6px 24px rgba(0,0,0,0.10);
        transform: scale(1.04);
    }
    .dashboard-icon {
        background: #F3F4F6;
        color: #CA2626;
        border-radius: 0.75rem;
        width: 3rem;
        height: 3rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 1rem;
    }
    .dashboard-title {
        font-size: 2rem;
        font-weight: 700;
        color: #CA2626;
        margin-bottom: 2.5rem;
        letter-spacing: -0.02em;
    }
    .dashboard-label {
        color: #CA2626;
        font-size: 0.95rem;
        font-weight: 500;
        margin-bottom: 0.25rem;
        letter-spacing: 0.02em;
    }
    .dashboard-value {
        font-size: 2.2rem;
        font-weight: 600;
        color: #222;
        margin-bottom: 0.5rem;
    }
    .dashboard-desc {
        color: #888;
        font-size: 1rem;
        font-weight: 400;
        margin-bottom: 0;
    }
    .dashboard-section-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #CA2626;
        margin-bottom: 1.25rem;
        letter-spacing: -0.01em;
        text-align: left;
    }
    .dashboard-btn {
        background: #CA2626;
        color: #fff;
        border-radius: 0.75rem;
        padding: 0.75rem 1.5rem;
        font-weight: 500;
        font-size: 1rem;
        border: none;
        transition: background 0.2s;
        display: inline-block;
        margin: 0.25rem 0.25rem 0 0;
    }
    .dashboard-btn:hover {
        background: #a81c1c;
    }
    @media (max-width: 768px) {
        .dashboard-title { font-size: 1.5rem; }
        .dashboard-card { padding: 1.2rem 0.8rem; }
        .dashboard-value { font-size: 1.5rem; }
        .dashboard-section-title { font-size: 1rem; }
    }
</style>

<div class="dashboard-bg min-h-screen pb-10">
    {{-- Pesan welcome --}}
    @if(session('welcome'))
        <div id="welcome-message" 
            class="bg-green-500 bg-opacity-85 text-white px-4 py-2 rounded-md shadow-md mb-6 w-96 max-w-xl mx-auto text-center 
                transition-all duration-700 ease-in-out overflow-hidden opacity-0 translate-y-3">
            {{ session('welcome') }}
        </div>

        <script>
            const msg = document.getElementById('welcome-message');

            // Animasi muncul
            setTimeout(() => {
                if (msg) {
                    msg.classList.remove('opacity-0', 'translate-y-3');
                }
            }, 50); 

            // animasi hilang
            setTimeout(() => {
                if (msg) {
                    msg.classList.add('opacity-0', 'translate-y-3', 'max-h-0', 'py-0', 'mb-0');
                    setTimeout(() => msg.remove(), 700); 
                }
            }, 5000);
        </script>
    @endif
    <div class="dashboard-title text-center animate-fade-in-up" style="animation-delay:0.1s;">
        Director Dashboard
    </div>

    <!-- KPI Cards -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-8 mb-10">
        <div class="dashboard-card animate-fade-in-up" style="animation-delay:0.2s;">
            <div class="dashboard-icon"><i class="fa-regular fa-folder-open"></i></div>
            <div class="dashboard-label">Total Projects</div>
            <div class="dashboard-value">{{ $totalProjects }}</div>
            <div class="dashboard-desc">All projects managed</div>
        </div>
        <div class="dashboard-card animate-fade-in-up" style="animation-delay:0.3s;">
            <div class="dashboard-icon"><i class="fa-solid fa-spinner"></i></div>
            <div class="dashboard-label">Active Projects</div>
            <div class="dashboard-value">{{ $activeProjects }}</div>
            <div class="dashboard-desc">Projects in progress</div>
        </div>
        <div class="dashboard-card animate-fade-in-up" style="animation-delay:0.4s;">
            <div class="dashboard-icon"><i class="fa-regular fa-circle-check"></i></div>
            <div class="dashboard-label">Completed Projects</div>
            <div class="dashboard-value">{{ $completedProjects }}</div>
            <div class="dashboard-desc">Projects finished</div>
        </div>
        <div class="dashboard-card animate-fade-in-up" style="animation-delay:0.5s;">
            <div class="dashboard-icon"><i class="fa-solid fa-tasks"></i></div>
            <div class="dashboard-label">Total Jobs</div>
            <div class="dashboard-value">{{ $totalJobs }}</div>
            <div class="dashboard-desc">All jobs in projects</div>
        </div>
        <div class="dashboard-card animate-fade-in-up" style="animation-delay:0.6s;">
            <div class="dashboard-icon"><i class="fa-solid fa-check-double"></i></div>
            <div class="dashboard-label">Jobs Completed This Month</div>
            <div class="dashboard-value">{{ $jobsCompletedThisMonth }}</div>
            <div class="dashboard-desc">Performance indicator</div>
        </div>
    </div>

    <!-- Charts -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">
        <div class="dashboard-card animate-fade-in-up" style="animation-delay:0.7s;">
            <div class="dashboard-section-title">Jobs Completed Per Month</div>
            <canvas id="jobsPerMonthChart" height="200"></canvas>
        </div>
        <div class="dashboard-card animate-fade-in-up" style="animation-delay:0.8s;">
            <div class="dashboard-section-title">Jobs Status Overview</div>
            <canvas id="jobsStatusChart" height="200"></canvas>
        </div>
    </div>

    <!-- Recent Activity -->
    @if(count($recentActivities))
    <div class="dashboard-card animate-fade-in-up mb-6" style="animation-delay:0.85s;">
        <div class="dashboard-section-title">Recent Activity</div>
        <ul class="text-left">
            @foreach($recentActivities as $activity)
                <li class="mb-3 border-b pb-2">
                    <strong>{{ $activity->job->title ?? 'Untitled Job' }}</strong> 
                    - {{ $activity->activity_note ?? 'No note' }} by {{ $activity->user->name ?? 'Unknown' }}
                    <br>
                    <small class="text-gray-500">{{ $activity->updated_at->diffForHumans() }}</small>
                </li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Quick Reports -->
    <div class="dashboard-card animate-fade-in-up" style="animation-delay:0.9s;">
        <div class="dashboard-section-title">Quick Reports</div>
        <a href="{{ route('reports.index') }}" class="dashboard-btn"><i class="fa-regular fa-file-alt mr-2"></i>All Reports</a>
        <a href="{{ route('reports.export.pdf') }}" class="dashboard-btn"><i class="fa-regular fa-file-pdf mr-2"></i>Export PDF</a>
        <a href="{{ route('reports.export.excel') }}" class="dashboard-btn"><i class="fa-regular fa-file-excel mr-2"></i>Export Excel</a>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Jobs Completed Per Month
    const jobsPerMonthCtx = document.getElementById('jobsPerMonthChart').getContext('2d');
    new Chart(jobsPerMonthCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($months) !!},
            datasets: [{
                label: 'Jobs Completed',
                data: {!! json_encode($jobsCompletedPerMonth) !!},
                backgroundColor: '#CA2626'
            }]
        },
        options: {
            responsive: true,
            plugins: { 
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1, // Naik per 1 job
                        precision: 0 // Pastikan tidak ada angka desimal
                    }
                }
            }
        }
    });

    // Jobs Status Overview
    const jobsStatusCtx = document.getElementById('jobsStatusChart').getContext('2d');
    new Chart(jobsStatusCtx, {
        type: 'pie',
        data: {
            labels: {!! json_encode($jobsStatusLabels) !!},
            datasets: [{
                data: {!! json_encode($jobsStatusData) !!},
                backgroundColor: {!! json_encode($jobsStatusColors) !!},
                borderColor: '#fff',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        pointStyle: 'circle'
                    }
                }
            }
        }
    });
</script>
@endsection
