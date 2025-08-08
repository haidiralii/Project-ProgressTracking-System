@extends('layouts.admin')

@section('title', 'ManPro - Dashboard Overview')

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
        background-color: ##F8F0F0
    }
    .dashboard-card {
        background: #fff;
        border-radius: 1rem;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        padding: 2rem 1.5rem;
        transition: box-shadow 0.2s;
    }
    .dashboard-card:hover {
        box-shadow: 0 6px 24px rgba(0,0,0,0.10);
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
    .dashboard-btn {
        background: #CA2626;
        color: #fff;
        border-radius: 0.75rem;
        padding: 0.75rem 1.5rem;
        font-weight: 500;
        font-size: 1rem;
        border: none;
        transition: background 0.2s;
    }
    .dashboard-btn:hover {
        background: #a81c1c;
    }
    .dashboard-section-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #CA2626;
        margin-bottom: 1.25rem;
        letter-spacing: -0.01em;
    }
    .dashboard-list-item {
        border-radius: 0.75rem;
        padding: 1rem 1.25rem;
        background: #F9FAFB;
        display: flex;
        align-items: center;
        margin-bottom: 0.75rem;
        transition: background 0.2s;
    }
    .dashboard-list-item:hover {
        background: #F3F4F6;
    }
    .dashboard-list-icon {
        color: #CA2626;
        margin-right: 1rem;
        font-size: 1.25rem;
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

    <div class="dashboard-title text-center animate-fade-in-up" style="animation-delay:0.1s;">Dashboard Overview</div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-10">
        <!-- Total Projects -->
        <div class="dashboard-card flex flex-col items-center text-center transition-all duration-300 hover:scale-105 hover:shadow-xl animate-fade-in-up" style="animation-delay:0.2s;">
            <div class="dashboard-icon">
                <i class="fa-regular fa-folder-open"></i>
            </div>
            <div class="dashboard-label">Total Projects</div>
            <div class="dashboard-value">{{ $totalProjects }}</div>
            <div class="dashboard-desc">All projects managed</div>
        </div>
        <!-- Total Users -->
        <div class="dashboard-card flex flex-col items-center text-center transition-all duration-300 hover:scale-105 hover:shadow-xl animate-fade-in-up" style="animation-delay:0.3s;">
            <div class="dashboard-icon">
                <i class="fa-regular fa-user"></i>
            </div>
            <div class="dashboard-label">Total Resources</div>
            <div class="dashboard-value">{{ $totalUsers }}</div>
            <div class="dashboard-desc">Active team members</div>
        </div>
        <!-- Completed Jobs -->
        <div class="dashboard-card flex flex-col items-center text-center transition-all duration-300 hover:scale-105 hover:shadow-xl animate-fade-in-up" style="animation-delay:0.4s;">
            <div class="dashboard-icon">
                <i class="fa-regular fa-circle-check"></i>
            </div>
            <div class="dashboard-label">Completed Jobs</div>
            <div class="dashboard-value">{{ $completedJobs }}</div>
            <div class="dashboard-desc">Tasks finished successfully</div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Recent Projects -->
        <div class="dashboard-card animate-fade-in-up transition-all duration-300 hover:scale-105 hover:shadow-xl" style="animation-delay:0.5s;">
            <div class="dashboard-section-title">Recent Projects</div>
            @forelse ($projects->take(5) as $project)
                <a href="{{ route('projects.show', $project->id) }}" class="dashboard-list-item hover:shadow transition-all duration-300 hover:scale-102 animate-fade-in-up" style="animation-delay:{{ 0.6 + $loop->index * 0.07 }}s;">
                    <span class="dashboard-list-icon"><i class="fa-regular fa-folder"></i></span>
                    <div class="flex-1">
                        <div class="font-semibold text-gray-900">{{ $project->name }}</div>
                        <div class="text-xs text-gray-500">{{ $project->jobs->count() }} jobs • {{ $project->created_at->format('M d, Y') }}</div>
                    </div>
                    <div class="text-xs text-gray-400">{{ $project->created_at->diffForHumans() }}</div>
                </a>
            @empty
                <div class="text-center py-8 text-gray-400 animate-fade-in-up" style="animation-delay:0.7s;">
                    <i class="fa-regular fa-folder-open text-3xl mb-3"></i>
                    <div>No recent projects to display.</div>
                </div>
            @endforelse
            <div class="flex justify-center mt-4">
                <a href="{{ route('projects.index') }}" class="dashboard-btn">View all projects</a>
            </div>
        </div>
        <!-- Today's Jobs -->
        <div class="dashboard-card animate-fade-in-up transition-all duration-300 hover:scale-105 hover:shadow-xl" style="animation-delay:0.6s;">
            <div class="dashboard-section-title">Today's Jobs</div>
            @forelse ($todayTasks->take(5) as $task)
                <div class="dashboard-list-item transition-all duration-300 hover:scale-102 hover:shadow animate-fade-in-up" style="animation-delay:{{ 0.7 + $loop->index * 0.07 }}s;">
                    <span class="dashboard-list-icon">
                        @if ($task->status == 'completed')
                            <i class="fa-regular fa-circle-check"></i>
                        @else
                            <i class="fa-regular fa-clock"></i>
                        @endif
                    </span>
                    <div class="flex-1">
                        <div class="font-semibold text-gray-900">{{ $task->title }}</div>
                        <div class="text-xs text-gray-500">{{ $task->project->name ?? 'No Project' }}</div>
                    </div>
                    <div class="text-xs text-gray-400">
                        {{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('H:i') : 'No Due Time' }}
                    </div>
                </div>
            @empty
                <div class="text-center py-8 text-gray-400 animate-fade-in-up" style="animation-delay:0.8s;">
                    <i class="fa-regular fa-clipboard text-3xl mb-3"></i>
                    <div>No jobs scheduled for today!</div>
                </div>
            @endforelse
            <div class="flex justify-center mt-4">
                <a href="{{ route('jobs.index') }}" class="dashboard-btn">View all jobs</a>
            </div>
        </div>
    </div>
</div>
@endsection