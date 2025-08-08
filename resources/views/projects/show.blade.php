@extends('layouts.admin')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-8">
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8 animate-fade-in">
        <!-- Header -->
        <div class="flex flex-wrap items-start justify-between mb-8 pr-4 gap-y-2">
            <!-- Kiri: Ikon + Nama Project -->
            <div class="flex items-start gap-3 flex-1 min-w-[200px]">
                <span class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-red-600 to-red-700 rounded-xl flex items-center justify-center shadow text-white">
                    <i class="fas fa-folder-open text-xl"></i>
                </span>
                <div>
                    <h1 class="text-xl font-bold text-red-700">{{ $project->name }}</h1>
                    <span class="inline-block mt-2 px-3 py-1 text-xs font-semibold rounded-full
                        @if($project->status == 'active') bg-green-100 text-green-700
                        @elseif($project->status == 'in_progress') bg-yellow-100 text-yellow-800
                        @elseif($project->status == 'completed') bg-blue-100 text-blue-700
                        @elseif($project->status == 'on_hold') bg-red-100 text-red-700
                        @else bg-gray-100 text-gray-700
                        @endif">
                        {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                    </span>
                </div>
            </div>

            <!-- Kanan: Tombol -->
            <div class="flex gap-2 flex-shrink-0 items-end">
                <a href="{{ route('projects.index') }}"
                    class="inline-flex items-center justify-center gap-2 min-w-[140px] h-10 px-4 bg-gray-100 text-red-700 rounded-xl font-semibold hover:bg-red-50 transition-all duration-200 shadow border border-red-200">
                    <i class="fas fa-arrow-left"></i>
                    <span>Back to List</span>
                </a>
                @if(auth()->user()->role !== 'operator')
                <a href="{{ route('projects.edit', $project->id) }}"
                    class="inline-flex items-center justify-center gap-2 min-w-[140px] h-10 px-4 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-xl font-semibold hover:from-red-700 hover:to-red-800 transition-all duration-200 shadow-lg">
                    <i class="fas fa-edit"></i>
                    <span>Edit Project</span>
                </a>
                @endif
            </div>
        </div>
        <!-- Project Information -->
        <div class="mb-8">
            <h3 class="text-base font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <span class="w-8 h-8 bg-gradient-to-br from-red-600 to-red-700 rounded-xl flex items-center justify-center shadow text-white">
                    <i class="fas fa-info-circle"></i>
                </span>
                Project Information
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <div class="text-sm font-medium text-gray-500 mb-1">Type</div>
                    <div class="text-sm font-medium text-gray-900">{{ $project->type ?? 'Internal Project' }}</div>
                </div>
                <div>
                    <div class="text-sm font-medium text-gray-500 mb-1">Status</div>
                    <div class="text-sm font-medium">
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold
                            @if($project->status == 'active') bg-green-100 text-green-700
                            @elseif($project->status == 'in_progress') bg-yellow-100 text-yellow-800
                            @elseif($project->status == 'completed') bg-blue-100 text-blue-700
                            @elseif($project->status == 'on_hold') bg-red-100 text-red-700
                            @else bg-gray-100 text-gray-700
                            @endif">
                            {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                        </span>
                    </div>
                </div>
                <div class="col-span-1 md:col-span-2">
                    <div class="text-sm font-medium text-gray-500 mb-1">Description</div>
                    <div class="text-sm font-medium text-gray-900">{{ $project->description ?? '-' }}</div>
                </div>
            </div>
        </div>

        <!-- Project Timeline -->
        <div class="mb-8">
            <h3 class="text-base font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <span class="w-8 h-8 bg-gradient-to-br from-red-600 to-red-700 rounded-xl flex items-center justify-center shadow text-white">
                    <i class="fas fa-calendar-alt"></i>
                </span>
                Project Timeline
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <div class="text-sm font-medium text-gray-500 mb-1">Start Date</div>
                    <div class="text-sm font-medium text-gray-900">
                        {{ $project->start_date ? \Carbon\Carbon::parse($project->start_date)->format('M d, Y') : '-' }}
                    </div>
                </div>
                <div>
                    <div class="text-sm font-medium text-gray-500 mb-1">End Date</div>
                    <div class="text-sm font-medium text-gray-900">
                        {{ $project->end_date ? \Carbon\Carbon::parse($project->end_date)->format('M d, Y') : '-' }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Progress Bar -->
        @php
            $progress = $project->progress_percentage ?? $project->progress ?? 0;
            $hasJobs = $project->jobs && $project->jobs->count() > 0;
            $barWidth = $hasJobs ? max($progress, 2) : 0;
        @endphp

        <div class="mb-8">
            <h3 class="text-base font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <span class="w-8 h-8 bg-gradient-to-br from-red-600 to-red-700 rounded-xl flex items-center justify-center shadow text-white">
                    <i class="fas fa-tasks"></i>
                </span>
                Progress
            </h3>

            <!-- Label -->
            <div class="flex items-center justify-between mb-1">
                <span class="text-sm font-medium text-gray-600">Project Progress</span>
                <span class="text-xs font-semibold text-gray-500">
                    {{ $hasJobs ? $progress . '%' : '0%' }}
                </span>
            </div>

            <!-- Bar Container -->
            <div class="relative w-full h-5 bg-gray-200 rounded-full overflow-hidden shadow-inner">
                <div class="absolute left-0 top-0 h-full rounded-full transition-all duration-700 ease-in-out
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

        <!-- Jobs List -->
        <div class="mb-8">
            <h3 class="text-base font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <span class="w-8 h-8 bg-gradient-to-br from-red-600 to-red-700 rounded-xl flex items-center justify-center shadow text-white">
                    <i class="fas fa-briefcase"></i>
                </span>
                Jobs
            </h3>

            @if($project->jobs->count())
                <ul class="space-y-3">
                    @foreach($project->jobs as $job)
                        <li class="flex justify-between items-center bg-white rounded-lg shadow-sm p-4">
                            <!-- Kiri: Nama Job + Status -->
                            <div class="flex flex-col">
                                <span class="font-semibold text-gray-800">{{ $job->name ?? $job->title }}</span>
                                <span class="text-xs font-semibold w-fit mt-1 rounded-full px-2 py-1"
                                    style="background-color: {{ $job->status_badge['bg'] }}; color: {{ $job->status_badge['text'] }};">
                                    {{ $job->status_label }}
                                </span>
                            </div>

                            <!-- Kanan: Operators -->
                            <div class="flex flex-wrap justify-end gap-1 max-w-xs">
                                @if($job->operators->count())
                                    @foreach($job->operators as $op)
                                        <span class="bg-red-100 text-red-700 px-2 py-1 rounded-full text-xs font-medium">
                                            {{ $op->name }}
                                        </span>
                                    @endforeach
                                @else
                                    <span class="bg-gray-100 text-gray-500 px-2 py-1 rounded-full text-xs font-medium">
                                        Not assigned
                                    </span>
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="text-gray-500">No jobs found for this project.</div>
            @endif
        </div>

    </div>
</div>

<style>
.animate-fade-in {
    animation: fadeIn 0.6s cubic-bezier(0.4, 0, 0.2, 1) forwards;
    opacity: 0;
}
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px);}
    to { opacity: 1; transform: translateY(0);}
}
</style>
@endsection