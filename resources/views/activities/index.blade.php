@extends('layouts.admin')

@section('title', 'ProTrack - Activity Overview')
@section('page-title', 'activities')

@section('content')
<div class="max-w-full mx-auto">

    <!-- Filter/Search Card -->
    <div class="w-full">
        <form method="GET" action="" class="bg-white/80 backdrop-blur-sm p-6 rounded-2xl shadow-xl border border-white/50 animate-fade-in mx-auto mt-0 mb-6 lg:max-w-[98%] lg:mx-auto">
            
            <!-- Filter Header -->
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-red-700 flex items-center gap-2">
                    <i class="fas fa-filter text-red-600"></i>
                    Activity Filters
                </h3>
                <div class="flex gap-2">
                    <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-xl font-semibold hover:from-red-700 hover:to-red-800 transition-all duration-200 shadow-lg hover:shadow-xl">
                        <i class="fas fa-search"></i>
                        Apply
                    </button>
                    <a href="{{ route('activities.index') }}" class="inline-flex items-center gap-2 px-6 py-2.5 bg-gray-100 text-red-700 rounded-xl font-semibold hover:bg-red-50 transition-all duration-200 shadow border border-red-200">
                        <i class="fas fa-sync"></i>
                        Reset
                    </a>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">

                <!-- Search Input -->
                <div class="space-y-2">
                    <label class="text-sm font-medium text-gray-700">Search Activities</label>
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Find Activities..." class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent bg-white transition-all duration-200" />
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>
                </div>

                <!-- Status Filter -->
                <div class="space-y-2">
                    <label class="text-sm font-medium text-gray-700">Project</label>
                    <select name="project" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent bg-white transition-all duration-200">
                        <option value="">All Project</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}" {{ request('project') == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Operator Filter -->
                @unless(auth()->user()->role === 'operator')
                <div class="space-y-2">
                    <label class="text-sm font-medium text-gray-700">Operator</label>
                    <select name="operator" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent bg-white transition-all duration-200">
                        <option value="">All Operator</option>
                        @foreach($operators as $operator)
                            <option value="{{ $operator->id }}" {{ request('operator') == $operator->id ? 'selected' : '' }}>{{ $operator->name }}</option>
                        @endforeach
                    </select>
                </div>
                @endunless

                <div class="space-y-2">
                    <label class="text-sm font-medium text-gray-700">Date</label>
                    <input type="date" name="date" value="{{ request('date') }}" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent bg-white transition-all duration-200" />
                </div>
            </div>
        </form>
    </div>

    <!-- Data List Card -->
    <div class="w-full">
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/50 overflow-hidden animate-fade-in
            mx-auto mt-0
            lg:max-w-[98%] lg:mx-auto
            " style="animation-delay:0.4s;">
            <!-- Table Header -->
            <div class="bg-gradient-to-r from-red-700 to-red-900 px-6 py-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-white">Activities Overview</h3>
                    <div class="text-sm text-white">{{ $activities->count() }} activities found</div>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white rounded-xl overflow-hidden">
                    <thead class="bg-red-50">
                        <tr>
                            <th class="py-3 px-6 text-left font-semibold text-red-600">Job Title</th>
                            <th class="py-3 px-6 text-left font-semibold text-red-600">Project</th>
                            <th class="py-3 px-6 text-left font-semibold text-red-600">Operator</th>
                            <th class="py-3 px-6 text-left font-semibold text-red-600">Status</th>
                            <th class="py-3 px-6 text-left font-semibold text-red-600">Date</th>
                            <th class="py-3 px-6 text-left font-semibold text-red-600">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($activities as $activity)
                            <tr>
                                <!-- Job Title -->
                                <td class="py-4 px-6 font-medium text-gray-900">
                                    {{ optional($activity->job)->title ?? '-' }}
                                </td>

                                <!-- Project -->
                                <td class="py-4 px-6 text-gray-700">
                                    {{ optional(optional($activity->job)->project)->name ?? '-' }}
                                </td>

                                <!-- Operator -->
                                <td class="py-4 px-6">
                                    @if($activity->user)
                                        <span class="inline-flex justify-center items-center px-3 py-1 rounded-full text-sm bg-red-100 text-red-800 min-w-[120px]">
                                            {{ $activity->user->name }}
                                        </span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>

                                <!-- Status -->
                                @php
                                    $maxStatusLength = $activities->max(fn($a) => strlen($a->status_label));
                                    $statusMinWidth = ($maxStatusLength * 8) + 20;
                                @endphp

                                <td class="py-4 px-6">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold"
                                        style="background-color: {{ $activity->status_badge['bg'] }};
                                            color: {{ $activity->status_badge['text'] }};
                                            min-width: {{ $statusMinWidth }}px;
                                            text-align: center;">
                                        {{ $activity->status_label }}
                                    </span>
                                </td>

                                <!-- Date -->
                                <td class="py-4 px-6 text-gray-700 whitespace-nowrap align-middle">
                                    {{ \Carbon\Carbon::parse($activity->activity_date)->format('d M Y') }}
                                </td>

                                <!-- Actions -->
                                <td class="py-4 px-6">
                                    <a href="{{ route('activities.show', $activity->id) }}"
                                        class="inline-flex items-center justify-center gap-1 px-3 py-1.5 rounded-md text-sm font-medium 
                                            bg-gray-100 text-red-700 border border-red-200
                                            hover:bg-red-700 hover:text-white hover:border-red-300
                                            transition-all duration-150 hover:scale-105 w-[100px]">
                                            <i class="fas fa-eye"></i>
                                        <span class="hidden md:inline">Details</span>
                                    </a>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-4 px-6 text-center text-gray-500">
                                    No activities found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px);}
        to { opacity: 1; transform: translateY(0);}
    }
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(10px);}
        to { opacity: 1; transform: translateY(0);}
    }
    .animate-fade-in {
        animation: fadeIn 0.6s cubic-bezier(0.4, 0, 0.2, 1) forwards;
        opacity: 0;
    }
    .animate-slide-up {
        animation: slideUp 0.4s cubic-bezier(0.4, 0, 0.2, 1) forwards;
        opacity: 0;
    }
</style>
@endsection
