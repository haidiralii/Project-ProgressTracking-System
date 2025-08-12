@extends('layouts.admin')

@section('title', 'ProTrack - Jobs Overview')
@section('page-title', 'Jobs')

@section('content')

    @if($errors->any())
        <div class="mb-6 p-4 rounded-xl bg-gradient-to-r from-red-100 to-rose-100 border border-red-300 shadow-sm animate-fade-in" style="animation-delay:0.2s;">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-red-600 rounded-full flex items-center justify-center">
                        <i class="fas fa-exclamation-triangle text-white text-sm"></i>
                    </div>
                </div>
                <div class="ml-3">
                    <p class="text-red-800 font-medium">{{ $errors->first() }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Advanced Filter Section -->
    <div class="mb-8">
        <form method="GET" action="" class="bg-white/80 backdrop-blur-sm p-6 rounded-2xl shadow-xl border border-white/50 animate-fade-in" style="animation-delay:0.3s;">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-red-700 flex items-center gap-2">
                    <i class="fas fa-filter text-red-600"></i>
                    Advanced Filters
                </h3>
                <div class="flex gap-2">
                    <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-red-600 to-red-700 
                        text-white rounded-xl font-semibold 
                        hover:from-red-700 hover:to-red-800 
                        transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
                        <i class="fas fa-search"></i>
                        Apply Filter
                    </button>
                    <a href="{{ route('jobs.index') }}" class="inline-flex items-center gap-2 px-6 py-2.5 bg-gray-100 text-red-700 rounded-xl font-semibold hover:bg-red-50 transition-all duration-200 shadow border border-red-200">
                        <i class="fas fa-refresh"></i>
                        Reset
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="space-y-2">
                    <label class="text-sm font-medium text-gray-700">Search Jobs</label>
                    <div class="relative overflow-visible z-30">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Find jobs..." class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-gray-500 focus:border-transparent bg-white transition-all duration-200"/>
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>
                </div>
                
                <div class="space-y-2">
                    <label class="text-sm font-medium text-gray-700">Project</label>
                    <select name="project" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-gray-500 focus:border-transparent bg-white transition-all duration-200">
                        <option value="">All Projects</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}" {{ request('project') == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-medium text-gray-700">Status</label>
                    <select name="status" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-gray-500 focus:border-transparent bg-white transition-all duration-200">
                        <option value="">All Status</option>
                        <option value="buat_baru" {{ request('status') == 'buat_baru' ? 'selected' : '' }}>New</option>
                        <option value="pengerjaan" {{ request('status') == 'pengerjaan' ? 'selected' : '' }}>In Progress</option>
                        <option value="tunda" {{ request('status') == 'tunda' ? 'selected' : '' }}>Pending</option>
                        <option value="tes" {{ request('status') == 'tes' ? 'selected' : '' }}>Testing</option>
                        <option value="perbaikan" {{ request('status') == 'perbaikan' ? 'selected' : '' }}>Revision</option>
                        <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-medium text-gray-700">Deadline</label>
                    <input type="date" name="deadline" value="{{ request('deadline') }}" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-gray-500 focus:border-transparent bg-white transition-all duration-200" />
                </div>
            </div>
        </form>
    </div>

    <!-- Jobs Table/Cards -->
    @if($jobs->isEmpty())
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/50 p-12 text-center animate-fade-in" style="animation-delay:0.4s;">
            <div class="max-w-md mx-auto">
                <div class="w-24 h-24 bg-gradient-to-br from-red-100 to-red-200 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-briefcase text-3xl text-red-400"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">No Jobs Yet!</h3>
                <p class="text-gray-500 mb-6">There are no jobs yet. Add one to begin managing your project.</p>
                <a href="#" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-xl font-semibold hover:from-red-700 hover:to-red-800 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
                    <i class="fas fa-plus"></i>
                    Add Job to Project
                </a>
            </div>
        </div>
    @else
        <div class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-xl border border-white/50 overflow-hidden animate-fade-in" style="animation-delay:0.5s;">
            <!-- Table Header -->
            <div class="bg-gradient-to-r from-red-700 to-red-900 px-6 py-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-white">Jobs Overview</h3>
                    <div class="text-sm text-white">{{ $jobs->count() }} jobs found</div>
                </div>
            </div>
            <!-- Table -->
            <div class="hidden lg:block relative overflow-visible z-30">
                <table class="w-full">
                    <thead>
                        <tr class="relative bg-gradient-to-r from-red-50 to-red-100 border-b border-red-200">
                            <th class="py-4 px-6 text-left font-semibold text-red-700">Job Title</th>
                            <th class="py-4 px-6 text-left font-semibold text-red-700">Project</th>
                            <th class="py-4 px-6 text-left font-semibold text-red-700">Operators</th>
                            <th class="py-4 px-6 text-left font-semibold text-red-700">Status</th>
                            <th class="py-4 px-6 text-left font-semibold text-red-700">Deadline</th>
                            <th class="py-4 px-6 text-center font-semibold text-red-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach ($jobs as $job)
                            <tr class="relative z-10 hover:bg-gray-50 transition-all duration-200 group animate-slide-up" style="animation-delay: {{ 0.6 + $loop->index * 0.05 }}s;">
                                <!-- column job title -->
                                <td class="py-4 px-6">
                                    <div class="font-semibold text-gray-900 group-hover:text-gray-700 transition-colors">
                                        {{ $job->title }}
                                    </div>
                                </td>
                                <!-- column project -->
                                <td class="py-4 px-6">
                                    <span class="text-gray-700">{{ $job->project->name ?? '-' }}</span>
                                </td>
                                <!-- column operators -->
                                <td class="py-4 px-6">
                                    @if($job->operators && $job->operators->count())
                                        @php
                                            $maxLength = $job->operators->max(fn($op) => strlen($op->name));
                                            $minWidth = ($maxLength * 8) + 20;
                                        @endphp
                                        <div class="flex flex-wrap gap-1">
                                            @foreach($job->operators as $operator)
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gradient-to-r from-red-100 to-red-200 text-red-700 border border-red-200"
                                                    style="min-width: {{ $minWidth }}px; text-align: center;">
                                                    {{ $operator->name }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-50 text-gray-500">
                                            Not assigned
                                        </span>
                                    @endif
                                </td>
                                <!-- column status -->
                                <td class="py-4 px-6">
                                    @php
                                        $maxStatusLength = $jobs->max(fn($j) => strlen($j->status_label));
                                        $statusMinWidth = ($maxStatusLength * 8) + 20;
                                    @endphp

                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold"
                                        style="background-color: {{ $job->status_badge['bg'] }};
                                                color: {{ $job->status_badge['text'] }};
                                                min-width: {{ $statusMinWidth }}px;
                                                text-align: center;">
                                        {{ $job->status_label }}
                                    </span>
                                </td>
                                <!-- column deadline -->
                                <td class="py-4 px-6 text-center whitespace-nowrap">
                                    @if($job->deadline)
                                        <span class="text-gray-700 font-medium">
                                            {{ $job->deadline->format('d M Y') }}
                                        </span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <!-- column actions -->
                                <td class="px-4 py-3 text-sm whitespace-nowrap align-top" style="min-width: 210px;">
                                    <div class="flex flex-wrap justify-center gap-2">

                                        {{-- View Details --}}
                                        <a href="{{ route('jobs.show', $job->id) }}"
                                            class="inline-flex items-center justify-center gap-1 px-3 py-1.5 rounded-md text-sm font-medium 
                                                bg-gray-100 text-red-700 border border-red-200
                                                hover:bg-red-700 hover:text-white hover:border-red-300
                                                transition-all duration-150 hover:scale-105 w-[100px]">
                                            <i class="fas fa-eye"></i>
                                            <span class="hidden md:inline">Details</span>
                                        </a>

                                        {{-- Update/Edit Job --}}
                                        @if(auth()->user()->role === 'admin' || (auth()->user()->role === 'operator' && $job->operators->contains(auth()->user()->id)))
                                            <a href="{{ auth()->user()->role === 'admin' ? route('jobs.edit', $job->id) : route('jobs.update_operator', $job->id) }}"
                                                class="inline-flex items-center justify-center gap-1 px-3 py-1.5 rounded-md text-sm font-medium 
                                                    bg-yellow-50 text-yellow-700 border border-red-200
                                                    hover:bg-yellow-600 hover:text-white hover:border-yellow-700
                                                    transition-all duration-150 hover:scale-105 w-[100px]">
                                                <i class="fas fa-edit"></i>
                                                <span class="hidden md:inline">
                                                    {{ auth()->user()->role === 'admin' ? 'Edit' : 'Update' }}
                                                </span>
                                            </a>
                                        @endif

                                        {{-- Mark Complete --}}
                                        @if(auth()->user()->role === 'operator' && $job->status !== 'selesai' && $job->operators->contains(auth()->user()->id))
                                            <form action="{{ route('jobs.complete', $job->id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit"
                                                    class="inline-flex items-center justify-center gap-1 px-3 py-1.5 rounded-md text-sm font-medium 
                                                        bg-green-50 text-green-700 border border-green-200
                                                        hover:bg-green-700 hover:text-white hover:border-green-800
                                                        transition-all duration-150 hover:scale-105 w-[100px]">
                                                    <i class="fas fa-check"></i>
                                                    <span class="hidden md:inline">Done</span>
                                                </button>
                                            </form>
                                        @endif

                                        {{-- Give Feedback --}}
                                        @if(auth()->user()->role === 'operator' && $job->status === 'tes' && $job->operators->contains(auth()->user()->id))
                                            <a href="{{ route('jobs.feedback.create', $job->id) }}"
                                                class="inline-flex items-center justify-center gap-1 px-3 py-1.5 rounded-md text-sm font-medium 
                                                    bg-red-50 text-red-700 border border-red-200
                                                    hover:bg-red-700 hover:text-white hover:border-red-800
                                                    transition-all duration-150 hover:scale-105 w-[100px]">
                                                <i class="fas fa-comment-dots"></i>
                                                <span class="hidden md:inline">Feedback</span>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes slideIn {
        from { opacity: 0; transform: translateX(-20px); }
        to { opacity: 1; transform: translateX(0); }
    }
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fadeIn 0.6s cubic-bezier(0.4, 0, 0.2, 1) forwards;
        opacity: 0;
    }
    .animate-slide-in {
        animation: slideIn 0.5s cubic-bezier(0.4, 0, 0.2, 1) forwards;
        opacity: 0;
    }
    .animate-slide-up {
        animation: slideUp 0.4s cubic-bezier(0.4, 0, 0.2, 1) forwards;
        opacity: 0;
    }
    .dropdown-menu {
        z-index: 9999 !important;
    }

    .dropdown-menu[data-popper-placement^="top"] {
        bottom: 100%;
        top: auto;
        margin-bottom: 0.5rem;
    }

    .dropdown-menu.show {
        display: block;
        position: absolute;
    }

    .table-wrapper {
        overflow-x: auto;
        position: relative;
        z-index: 1;
    }
</style>
@endsection