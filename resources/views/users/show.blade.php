@extends('layouts.admin')

@section('title', 'User Detail')
@section('page-title', 'User Detail')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-8">
    <div class="bg-white rounded-xl shadow-md border border-gray-200 p-8 animate-fade-in-up" style="animation-delay:0.1s;">
        <!-- Header -->
        <div class="flex items-center gap-4 mb-8">
            <button type="button" onclick="window.history.back()"
                class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-xl font-semibold hover:from-maroon-dark hover:to-maroon-primary transition-all duration-200 shadow hover:scale-105 hover:shadow-xl">
                <i class="fas fa-arrow-left"></i>
                Back
            </button>
            <div>
                <h2 class="text-xl font-semibold text-maroon mb-1">User Detail</h2>
                <p class="text-gray-500 text-sm">Detail information for user</p>
            </div>
        </div>

        <!-- User Card -->
        <div class="flex flex-col items-center mb-8">
            <div class="w-20 h-20 bg-gradient-to-br from-red-600 to-red-700 rounded-full flex items-center justify-center shadow text-white text-3xl mb-4">
                <i class="fas fa-user"></i>
            </div>
            <h2 class="text-2xl font-semibold text-gray-900 mb-1">{{ $user->name }}</h2>
            <p class="text-gray-500 text-base mb-2">{{ $user->email }}</p>
            <span class="inline-block px-4 py-1 rounded-full font-semibold text-xs shadow"
                  style="background-color: var(--maroon-lighter); color: var(--maroon-primary);">
                {{ ucfirst($user->role) }}
            </span>
            <div class="text-xs text-gray-400 mt-2">Created: {{ $user->created_at->format('d M Y') }}</div>
        </div>

        @if($user->role === 'operator')
        <div class="mb-8">
            <h3 class="text-base font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <span class="w-8 h-8 bg-gradient-to-br from-red-600 to-red-700 rounded-xl flex items-center justify-center shadow text-white">
                    <i class="fas fa-briefcase"></i>
                </span>
                Assigned Jobs
            </h3>
            @if($user->jobs && $user->jobs->count())
            <ul class="space-y-3">
                @foreach($user->jobs as $job)
                <li class="bg-gray-50 border border-gray-200 rounded-xl p-4 flex flex-col md:flex-row md:items-center md:justify-between gap-2 shadow-sm animate-fade-in-up" style="animation-delay:0.2s;">
                    <div>
                        <span class="font-semibold text-gray-800">{{ $job->title }}</span>
                        <span class="ml-2 text-xs font-semibold rounded-full px-2 py-1"
                              style="background-color: {{ $job->status_badge['bg'] ?? '#F3F4F6' }}; color: {{ $job->status_badge['text'] ?? '#374151' }};">
                            {{ $job->status_label ?? ucfirst($job->status) }}
                        </span>
                        @if(isset($job->pivot->assigned_at))
                        <span class="ml-2 text-xs text-gray-500">Assigned: {{ \Carbon\Carbon::parse($job->pivot->assigned_at)->format('d M Y') }}</span>
                        @endif
                    </div>
                    <a href="{{ route('jobs.show', $job->id) }}"
                       class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-xl font-semibold hover:from-maroon-dark hover:to-maroon-primary transition-all duration-200 shadow hover:scale-105 hover:shadow-xl">
                        <i class="fas fa-eye"></i>
                        Detail
                    </a>
                </li>
                @endforeach
            </ul>
            @else
            <div class="text-gray-400 text-center py-4">No jobs assigned.</div>
            @endif
        </div>
        @endif
    </div>
</div>

<style>
:root {
    --maroon-primary: #CA2626;
    --maroon-light: #E04B4B;
    --maroon-dark: #9B1A1A;
    --maroon-lighter: #F8E4E4;
}
.text-maroon { color: var(--maroon-primary); }
.bg-maroon { background-color: var(--maroon-primary); }
.bg-maroon-light { background-color: var(--maroon-light); }
.bg-maroon-dark { background-color: var(--maroon-dark); }
.bg-maroon-lighter { background-color: var(--maroon-lighter); }
.animate-fade-in-up {
    opacity: 0;
    animation: fadeInUp 0.6s cubic-bezier(0.4,0,0.2,1) forwards;
}
@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(24px);}
    to { opacity: 1; transform: translateY(0);}
}
</style>
@endsection
