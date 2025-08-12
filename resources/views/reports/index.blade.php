@extends('layouts.admin')

@section('title', 'ProTrack - Reports Overview')
@section('page-title', 'Reports')

@section('content')

<div class="max-w-full mx-auto px-6">
    <!-- Tabs Navigation -->
    <div class="mb-6">
        <div class="flex gap-8 border-b border-gray-200">
            @foreach (['daily' => 'Daily', 'monthly' => 'Monthly', 'project' => 'Per Project'] as $key => $label)
                <a href="{{ route('reports.index', array_merge(request()->all(), ['type' => $key])) }}"
                   class="pb-3 px-1 font-medium text-sm transition-all duration-200 {{ ($type ?? 'daily') === $key ? 'border-b-2 border-red-600 text-red-600' : 'text-gray-500 hover:text-red-600 hover:border-b-2 hover:border-red-300' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>
    </div>

    <!-- Filter Card -->
    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/50 p-6 mb-6 animate-fade-in">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-red-700 flex items-center gap-2">
                <i class="fas fa-filter text-red-600"></i>
                Report Filters
            </h3>
        </div>
        
        <form method="GET" action="{{ route('reports.index') }}" class="flex flex-wrap items-end gap-4">
            <input type="hidden" name="type" value="{{ $type ?? 'daily' }}">

            @if (($type ?? 'daily') === 'daily')
                <div class="space-y-2">
                    <label class="text-sm font-medium text-gray-700">Date</label>
                    <input type="date" name="date" value="{{ $date ?? now()->format('Y-m-d') }}" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent bg-white transition-all duration-200">
                </div>
            @elseif (($type ?? 'daily') === 'monthly')
                <div class="space-y-2">
                    <label class="text-sm font-medium text-gray-700">Month</label>
                    <input type="month" name="month" value="{{ $month ?? now()->format('Y-m') }}" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent bg-white transition-all duration-200">
                </div>
            @elseif (($type ?? 'daily') === 'project')
                <div class="space-y-2">
                    <label class="text-sm font-medium text-gray-700">Project</label>
                    <select name="project_id" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent bg-white transition-all duration-200">
                        <option value="">-- Select Project --</option>
                        @foreach ($projects ?? [] as $proj)
                            <option value="{{ $proj->id }}" {{ ($projectId ?? '') == $proj->id ? 'selected' : '' }}>
                                {{ $proj->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif

            <div class="flex gap-2">
                <button type="submit" 
                        class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-xl font-semibold hover:from-red-700 hover:to-red-800 transition-all duration-200 shadow-lg hover:shadow-xl">
                    <i class="fas fa-search"></i>
                    Apply Filters
                </button>

                <a href="{{ $activities->isNotEmpty() ? route('reports.export.excel', request()->all()) : '#' }}" 
                class="inline-flex items-center gap-2 px-6 py-2.5 bg-gray-100 text-red-700 rounded-xl font-semibold hover:bg-red-50 transition-all duration-200 shadow border border-red-200
                        {{ $activities->isEmpty() ? 'opacity-50 cursor-not-allowed pointer-events-none' : '' }}">
                    <i class="fas fa-file-excel"></i>
                    Excel
                </a>

                <a href="{{ $activities->isNotEmpty() ? route('reports.export.pdf', request()->all()) : '#' }}" 
                    class="inline-flex items-center gap-2 px-6 py-2.5 bg-gray-100 text-red-700 rounded-xl font-semibold hover:bg-red-50 transition-all duration-200 shadow border border-red-200
                        {{ $activities->isEmpty() ? 'opacity-50 cursor-not-allowed pointer-events-none' : '' }}">
                    <i class="fas fa-file-pdf"></i>
                    PDF
                </a>
            </div>
        </form>
    </div>

    <!-- Summary Card -->
    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/50 p-6 mb-6 animate-fade-in" style="animation-delay: 0.2s;">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-gradient-to-r from-gray-100 to-gray-200 rounded-full flex items-center justify-center">
                <i class="fas fa-chart-bar text-gray-400"></i>
            </div>
            <div>
                <p class="text-sm text-gray-600">Total Activities Found</p>
                <p class="text-2xl font-bold text-gray-800">{{ ($activities ?? collect())->count() }}</p>
            </div>
        </div>
    </div>

    <!-- Data Table Card -->
    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/50 overflow-hidden animate-fade-in" style="animation-delay: 0.4s;">
        @if(($activities ?? collect())->isEmpty())
            <div class="p-12 text-center">
                <div class="max-w-md mx-auto">
                    <div class="w-24 h-24 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-chart-bar text-3xl text-gray-400"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">No Activities Found!</h3>
                    <p class="text-gray-500 mb-6">There are no recorded activities found.</p>
                </div>
            </div>
        @else

        <!-- Table Content -->
        <div class="overflow-x-auto">
            <table class="w-full min-w-[900px]">
                <thead>
                    <tr class="bg-gradient-to-r from-red-50 to-red-100 border-b border-red-200">
                        <th class="py-3 px-4 text-sm font-bold text-red-700 text-left">Project</th>
                        <th class="py-3 px-4 text-sm font-bold text-red-700 text-left">Job</th>
                        <th class="py-3 px-4 text-sm font-bold text-red-700 text-left">Operator</th>
                        <th class="py-3 px-4 text-sm font-bold text-red-700 text-left">Date</th>
                        <th class="py-3 px-4 text-sm font-bold text-red-700 text-left">Activity Description</th>
                    </tr>
                </thead>
                <!-- Table Body -->
                <tbody class="divide-y divide-gray-50">
                    @foreach($activities as $activity)
                        <tr class="hover:bg-gray-50 transition-all duration-200 group animate-slide-up" style="animation-delay: {{ 0.5 + $loop->index * 0.05 }}s;">
                            <!-- Kolom Project -->
                            <td class="py-3 px-4 text-sm font-semibold text-gray-900 text-left">
                                {{ $activity->job->project->name ?? '-' }}
                            </td>

                            <!-- Kolom Job -->
                            <td class="py-3 px-4 text-sm text-gray-700 text-left">
                                {{ $activity->job->title ?? '-' }}
                            </td>

                            <!-- Kolom Operator -->
                            @php
                                $maxNameLength = collect($activities ?? [])->max(fn($a) => strlen($a->user->name ?? ''));
                                $minWidthRem = ($maxNameLength * 0.6) + 2; 
                            @endphp
                            <td class="py-3 px-4 text-left">
                                <span class="inline-flex items-center justify-center px-3 py-1 rounded-full text-xs font-semibold {{ $activity->user->role_badge_class ?? '' }}"
                                    style="min-width: {{ $minWidthRem }}rem; text-align: center;">
                                    {{ $activity->user->name ?? '-' }}
                                </span>
                            </td>

                            <!-- Kolom Tanggal -->
                            <td class="py-4 px-6 text-gray-700 whitespace-nowrap align-middle">
                                {{ \Carbon\Carbon::parse($activity->activity_date ?? now())->format('d-m-Y') }}
                            </td>

                            <!-- Kolom Deskripsi Aktivitas -->
                            <td class="py-3 px-4 text-sm text-gray-700 text-left">
                                {{ $activity->activity_note ?? '-' }}
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
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