@extends('layouts.admin')

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

                <a href="{{ route('reports.export.excel', request()->all()) }}" 
                   class="inline-flex items-center gap-2 px-6 py-2.5 bg-gray-100 text-red-700 rounded-xl font-semibold hover:bg-red-50 transition-all duration-200 shadow border border-red-200">
                    <i class="fas fa-file-excel"></i>
                    Excel
                </a>

                <a href="{{ route('reports.export.pdf', request()->all()) }}" 
                    class="inline-flex items-center gap-2 px-6 py-2.5 bg-gray-100 text-red-700 rounded-xl font-semibold hover:bg-red-50 transition-all duration-200 shadow border border-red-200">                    <i class="fas fa-file-pdf"></i>
                    PDF
                </a>
            </div>
        </form>
    </div>

    <!-- Summary Card -->
    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/50 p-6 mb-6 animate-fade-in" style="animation-delay: 0.2s;">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-gradient-to-r from-red-100 to-red-200 rounded-full flex items-center justify-center">
                <i class="fas fa-chart-bar text-red-600"></i>
            </div>
            <div>
                <p class="text-sm text-gray-600">Total Activities Found</p>
                <p class="text-2xl font-bold text-gray-800">{{ ($activities ?? collect())->count() }}</p>
            </div>
        </div>
    </div>

    <!-- Data Table Card -->
    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/50 overflow-hidden animate-fade-in" style="animation-delay: 0.4s;">
        <!-- Table Header -->
        <div class="bg-gradient-to-r from-red-700 to-red-900 px-6 py-4">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-white">Reports Data</h3>
                <div class="text-sm text-white">{{ ($activities ?? collect())->count() }} records found</div>
            </div>
        </div>

        <!-- Table Content -->
        <div class="overflow-x-auto">
            <table class="w-full min-w-[900px]">
                <thead>
                    <tr class="bg-gradient-to-r from-red-50 to-red-100 border-b border-red-200">
                        <th class="py-4 px-6 font-bold text-red-700 text-center">Project</th>
                        <th class="py-4 px-6 font-bold text-red-700 text-center">Job</th>
                        <th class="py-4 px-6 font-bold text-red-700 text-center">Operator</th>  
                        <th class="py-4 px-6 font-bold text-red-700 text-center">Date</th>
                        <th class="py-4 px-6 font-bold text-red-700 text-center">Activity Description</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse(($activities ?? []) as $activity)
                        <tr class="hover:bg-gray-50 transition-all duration-200 group animate-slide-up" style="animation-delay: {{ 0.5 + $loop->index * 0.05 }}s;">
                            <td class="py-4 px-6 text-center align-middle font-semibold text-gray-900">
                                {{ $activity->job->project->name ?? '-' }}
                            </td>
                            <td class="py-4 px-6 text-center align-middle text-gray-700">
                                {{ $activity->job->title ?? '-' }}
                            </td>
                            <td class="py-4 px-6 text-center align-middle">
                                <span class="inline-block px-3 py-1 rounded-full bg-gradient-to-r from-red-100 to-red-200 text-red-700 text-xs font-semibold shadow-sm border border-red-200">
                                    {{ $activity->user->name ?? '-' }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-center align-middle text-gray-700">
                                {{ \Carbon\Carbon::parse($activity->activity_date ?? now())->format('d-m-Y') }}
                            </td>
                            <td class="py-4 px-6 text-center align-middle">
                                <span class="block px-3 py-2 rounded bg-gradient-to-r from-red-50 to-red-100 text-red-700 text-xs font-medium shadow border border-red-100">
                                    {{ $activity->activity_note ?? '-' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center gap-3">
                                    <i class="fas fa-inbox text-4xl text-gray-300"></i>
                                    <p class="text-lg font-medium">No report data found</p>
                                    <p class="text-sm">Try adjusting your filters or check back later.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
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