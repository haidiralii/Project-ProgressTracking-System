@extends('layouts.admin')

@section('title', 'Projects Overview')

@section('content')
<style>
    .animate-fade-in-up {
        animation: fadeInUp 0.6s cubic-bezier(0.4,0,0.2,1) forwards;
        opacity: 0;
    }
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(24px);}
        to { opacity: 1; transform: translateY(0);}
    }
    .table-header {
        background: #F8F0F0;
        font-weight: 600;
        color: #CA2626;
    }
    .table-row {
        background: #fff;
        transition: box-shadow 0.2s, transform 0.2s;
    }
    .table-row:hover {
        box-shadow: 0 6px 24px rgba(202,38,38,0.10);
        transform: scale(1.01);
    }
    .status-badge {
        font-weight: 500;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.85rem;
        transition: box-shadow 0.2s, background 0.2s;
        box-shadow: 0 1px 4px rgba(202,38,38,0.08);
        display: inline-block;
    }
    .status-active { background-color: #dcfce7; color: #15803d; }
    .status-in_progress { background-color: #fefce8; color: #a16207; }
    .status-completed { background-color: #e0f2f7; color: #0e7490; }
    .status-tunda { background-color: #fee2e2; color: #b91c1c; }
    .status-perbaikan { background-color: #dbeafe; color: #2563eb; }
    .maroon-btn {
        background: #CA2626;
        color: #fff;
        border-radius: 0.75rem;
        padding: 0.5rem 1.25rem;
        font-weight: 600;
        font-size: 0.95rem;
        border: none;
        transition: background 0.2s, box-shadow 0.2s, transform 0.2s;
        box-shadow: 0 1px 4px rgba(202,38,38,0.08);
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    .maroon-btn:hover {
        background: #a81c1c;
        box-shadow: 0 6px 24px rgba(202,38,38,0.10);
        transform: scale(1.04);
    }
    .progress-bar-bg {
        background: #F3F4F6;
        border-radius: 9999px;
        height: 8px;
        width: 100%;
        overflow: hidden;
    }
    .progress-bar-fill {
        background: linear-gradient(90deg, #CA2626 60%, #E04B4B 100%);
        height: 8px;
        border-radius: 9999px;
        transition: width 0.4s cubic-bezier(0.4,0,0.2,1);
    }
    @media (max-width: 768px) {
        .table-responsive { overflow-x: auto; }
        .table-header, .table-row { font-size: 0.95rem; }
    }
</style>

<div class="dashboard-bg min-h-screen pb-10">
    <!-- Breadcrumb & Header -->
    <div class="mb-8 animate-fade-in-up" style="animation-delay:0.15s;">
        <div class="flex items-center gap-3">
            <h1 class="text-2xl font-bold text-red-600 leading-tight">
                Projects Overview
            </h1>
        </div>
        <p class="text-gray-700 text-sm mt-1 pl-0 animate-fade-in-up" style="animation-delay:0.18s;">
            List of all projects managed by Director
        </p>
    </div>

    <!-- Search & Filter -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6 animate-fade-in-up" style="animation-delay:0.2s;">
        <form method="GET" action="{{ route('projects.director') }}" class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
            <input type="text" name="search" value="{{ request('search') }}"
                class="rounded-xl border px-4 py-2 text-sm shadow focus:ring-maroon focus:border-maroon transition-all duration-300 w-full sm:w-64"
                placeholder="Search project name...">
            <select name="status" class="rounded-xl border px-4 py-2 text-sm pr-8 min-w-max shadow transition-all duration-300 focus:ring-maroon focus:border-maroon">
                <option value="">All Status</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="tunda" {{ request('status') == 'tunda' ? 'selected' : '' }}>Tunda</option>
                <option value="perbaikan" {{ request('status') == 'perbaikan' ? 'selected' : '' }}>Perbaikan</option>
            </select>
            <button type="submit" class="maroon-btn">
                <i class="fas fa-search"></i> Search
            </button>
        </form>
    </div>

    <!-- Projects Table -->
    <div class="table-responsive rounded-2xl shadow-lg bg-white animate-fade-in-up" style="animation-delay:0.25s;">
        <!-- Table Header Gradient -->
        <div class="bg-gradient-to-r from-red-600 to-red-700 px-6 py-4 rounded-t-xl">
            <div class="flex items-center justify-between text-white">
                <span class="font-semibold text-lg">Projects List</span>
                <span class="text-sm opacity-80">Total: {{ $projects->count() }}</span>
            </div>
        </div>

        <table class="min-w-full w-full border-collapse">
            <thead>
                <tr class="table-header bg-gradient-to-r from-red-50 to-red-100 border-b border-red-200">
                    <th class="px-6 py-4 text-left">Project Title</th>
                    <th class="px-6 py-4 text-left">Status</th>
                    <th class="px-6 py-4 text-left">Start Date</th>
                    <th class="px-6 py-4 text-left">Deadline</th>
                    <th class="px-6 py-4 text-left">Progress</th>
                    <th class="px-6 py-4 text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($projects as $project)
                <tr class="table-row border-b last:border-none">
                    <td class="px-6 py-4 font-semibold text-gray-900">
                        <a href="{{ route('projects.show', $project->id) }}" class="hover:text-maroon transition">
                            {{ $project->name }}
                        </a>
                    </td>
                    <td class="px-6 py-4">
                        <span class="status-badge status-{{ $project->status_badge }}">
                            {{ $project->status_label }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-gray-700">
                        {{ $project->start_date ? \Carbon\Carbon::parse($project->start_date)->format('M d, Y') : '-' }}
                    </td>
                    <td class="px-6 py-4 text-gray-700">
                        {{ $project->end_date ? \Carbon\Carbon::parse($project->deadline)->format('M d, Y') : '-' }}
                    </td>

                    <!-- Progress Bar -->
                    <td class="px-6 py-4 w-56 align-middle">
                        @php
                            $progress = $project->progress_percentage ?? 0;
                            $hasJobs = $project->jobs && $project->jobs->count() > 0;
                            $barWidth = $hasJobs ? max($progress, 2) : 0; // minimal 2% agar tetap terlihat
                        @endphp

                        <div class="flex flex-col items-start w-full">

                            <!-- Label -->
                            <div class="flex items-center justify-between w-full mb-1">
                                <span class="text-sm font-medium text-gray-600">Progress</span>
                                <span class="text-xs font-semibold text-gray-500">
                                    {{ $hasJobs ? $progress . '%' : '0%' }}
                                </span>
                            </div>

                            <!-- Bar Container -->
                            <div class="relative w-full h-5 bg-gray-200 rounded-full overflow-hidden shadow-inner">
                                <div class="absolute left-0 top-0 h-full rounded-full
                                            transition-all duration-700 ease-in-out
                                            {{ $progress >= 100 ? 'bg-green-500' : 'bg-gradient-to-r from-red-600 to-red-700' }}"
                                    style="width: {{ $barWidth }}%;">
                                </div>
                            </div>

                            <!-- Message if no jobs -->
                            @unless($hasJobs)
                                <div class="text-xs text-gray-400 mt-2 italic">
                                    No jobs added yet — progress will show when jobs are assigned.
                                </div>
                            @endunless
                        </div>
                    </td>
                    
                    <td class="px-6 py-4 text-center">
                        <div class="flex justify-center flex-wrap gap-2">
                            <a href="{{ route('projects.show', $project->id) }}"
                            class="inline-flex items-center gap-2 bg-red-600 text-white rounded-lg px-4 py-2 text-sm font-semibold hover:bg-maroon-700 transition duration-200 shadow-sm"
                            title="View Project">
                                <i class="fas fa-eye"></i>
                                <span>View</span>
                            </a>
                            <a href="{{ route('reports.project', $project->id) }}"
                            class="inline-flex items-center gap-2 bg-gray-100 text-gray-800 rounded-lg px-4 py-2 text-sm font-semibold hover:bg-gray-200 transition duration-200 shadow-sm"
                            title="Project Report">
                                <i class="fas fa-file-alt"></i>
                                <span>Report</span>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-folder-open text-4xl mb-3 text-maroon"></i>
                            <div class="font-semibold text-lg mb-2">No Projects Found</div>
                            <div class="text-sm">Try adjusting your search or filter.</div>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($projects instanceof \Illuminate\Pagination\LengthAwarePaginator && $projects->hasPages())
    <div class="mt-8 flex justify-center animate-fade-in-up" style="animation-delay: 0.5s;">
        {{ $projects->links() }}
    </div>
    @endif
</div>
@endsection