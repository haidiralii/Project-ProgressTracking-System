@extends('layouts.admin')

@section('content')
<div class="max-w-full mx-auto">

    <!-- Filter/Search Card -->
    <div class="w-full">
        <form method="GET" action="" class="bg-white/80 backdrop-blur-sm p-6 rounded-2xl shadow-xl border border-white/50 animate-fade-in
            mx-auto mt-0 mb-6
            lg:max-w-[98%] lg:mx-auto
            ">
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
                <div class="space-y-2">
                    <label class="text-sm font-medium text-gray-700">Search Activities</label>
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Find Activities..." class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent bg-white transition-all duration-200" />
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-medium text-gray-700">Project</label>
                    <select name="project" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent bg-white transition-all duration-200">
                        <option value="">All Project</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}" {{ request('project') == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-medium text-gray-700">Operator</label>
                    <select name="operator" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent bg-white transition-all duration-200">
                        <option value="">All Operator</option>
                        @foreach($operators as $operator)
                            <option value="{{ $operator->id }}" {{ request('operator') == $operator->id ? 'selected' : '' }}>{{ $operator->name }}</option>
                        @endforeach
                    </select>
                </div>
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
                <table class="w-full min-w-[900px]">
                    <thead>
                        <tr class="bg-gradient-to-r from-red-50 to-red-100 border-b border-red-200">
                            <th class="py-4 px-6 font-bold text-red-700 text-center">Project</th>
                            <th class="py-4 px-6 font-bold text-red-700 text-center">Job</th>
                            <th class="py-4 px-6 font-bold text-red-700 text-center">Operator</th>
                            <th class="py-4 px-6 font-bold text-red-700 text-center">Date</th>
                            <th class="py-4 px-6 font-bold text-red-700 text-center">Activity</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($activities as $activity)
                            <tr class="hover:bg-gray-50 transition-all duration-200 group animate-slide-up" style="animation-delay: {{ 0.5 + $loop->index * 0.05 }}s;">
                                <td class="py-4 px-6 text-center align-middle font-semibold text-gray-900">{{ $activity->job->project->name ?? '-' }}</td>
                                <td class="py-4 px-6 text-center align-middle text-gray-700">{{ $activity->job->title ?? '-' }}</td>
                                <td class="py-4 px-6 text-center align-middle">
                                    <span class="inline-block px-3 py-1 rounded-full bg-gradient-to-r from-red-100 to-red-200 text-red-700 text-xs font-semibold shadow-sm border border-red-200">{{ $activity->user->name ?? '-' }}</span>
                                </td>
                                <td class="py-4 px-6 text-center align-middle text-gray-700">{{ \Carbon\Carbon::parse($activity->activity_date)->format('d-m-Y') }}</td>
                                <td class="py-4 px-6 text-center align-middle">
                                    <div class="flex items-center justify-between gap-2">
                                        <span class="block px-3 py-2 rounded bg-gradient-to-r from-red-50 to-red-100 text-red-700 text-xs font-medium shadow flex-1 text-left border border-red-100">
                                            {{ $activity->activity_note ?? '-' }}
                                        </span>
                                        @if(auth()->user()->role === 'admin' || auth()->user()->role === 'operator')
                                            <a href="{{ route('activities.show', $activity->id) }}" 
                                               class="inline-flex items-center gap-2 px-3 py-1 rounded-lg bg-gray-100 text-gray-700 hover:bg-red-700 hover:text-white font-semibold text-xs shadow-sm transition border border-gray-200 ml-2 whitespace-nowrap">
                                                <i class="fas fa-eye"></i> Detail
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-6 text-center text-gray-500 mb-6">No activity logs found.</td>
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
