@extends('layouts.admin')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-8">
    <div class="bg-white rounded-xl shadow-md border border-gray-200 p-8 animate-fade-in-up" style="animation-delay:0.1s;">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-lg font-semibold text-maroon">{{ $job->title }}</h1>
                <span class="px-3 py-1 rounded-full text-xs font-semibold shadow-sm"
                    style="background-color: {{ $job->status_badge['bg'] }}; color: {{ $job->status_badge['text'] }};">
                    {{ $job->status_label }}
                </span>
            </div>
            <a href="{{ route('jobs.index') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-xl font-semibold hover:from-maroon-dark hover:to-maroon-primary transition-all duration-200 shadow-lg hover:scale-105 hover:shadow-xl animate-fade-in-up"
               style="animation-delay:0.2s;">
                <i class="fas fa-arrow-left"></i>
                Back to Jobs
            </a>
        </div>

        <!-- Job Information -->
        <div class="mb-8">
            <h3 class="text-base font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <span class="w-8 h-8 bg-gradient-to-br from-red-600 to-red-700 rounded-xl flex items-center justify-center shadow text-white">
                    <i class="fas fa-info-circle"></i>
                </span>
                Job Information
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <div class="text-sm font-semibold text-gray-700 mb-1">Project</div>
                    <div class="text-sm font-medium text-gray-900">{{ $job->project->name ?? '-' }}</div>
                </div>
                <div>
                    <div class="text-sm font-semibold text-gray-700 mb-1">Requested At</div>
                    <div class="text-sm font-medium text-gray-900">{{ $job->requested_at ? $job->requested_at->format('d M Y') : '-' }}</div>
                </div>
                <div>
                    <div class="text-sm font-semibold text-gray-700 mb-1">Deadline</div>
                    <div class="text-sm font-medium text-gray-900">{{ $job->deadline ? $job->deadline->format('d M Y') : '-' }}</div>
                </div>
            </div>
        </div>

        <!-- Operators -->
        <div class="mb-8">
            <h3 class="text-base font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <span class="w-8 h-8 bg-gradient-to-br from-red-600 to-red-700 rounded-xl flex items-center justify-center shadow text-white">
                    <i class="fas fa-users"></i>
                </span>
                Operators
            </h3>
            @if($job->operators && $job->operators->count())
                <div class="flex flex-wrap gap-2">
                    @foreach($job->operators as $operator)
                        <span class="inline-block px-2 py-1 rounded-full bg-maroon-lighter text-maroon text-xs font-semibold shadow">{{ $operator->name }}</span>
                    @endforeach
                </div>
            @else
                <span class="inline-block px-2 py-1 rounded-full bg-gray-100 text-gray-500 text-xs font-semibold">Not assigned</span>
            @endif
        </div>

        <!-- Description -->
        <div class="mb-8">
            <h3 class="text-base font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <span class="w-8 h-8 bg-gradient-to-br from-red-600 to-red-700 rounded-xl flex items-center justify-center shadow text-white">
                    <i class="fas fa-align-left"></i>
                </span>
                Description
            </h3>
            <div class="text-gray-700">{!! nl2br(e($job->description)) ?: '<span class="text-gray-400">No description provided.</span>' !!}</div>
        </div>

        <!-- Activities -->
        <div class="mb-8">
            <h3 class="text-base font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <span class="w-8 h-8 bg-gradient-to-br from-red-600 to-red-700 rounded-xl flex items-center justify-center shadow text-white">
                    <i class="fas fa-list-check"></i>
                </span>
                Activities
            </h3>
            @if($job->activities && $job->activities->count())
                <ul class="space-y-3">
                    @foreach($job->activities as $activity)
                        <li class="border-b pb-2">
                            <div class="font-semibold text-gray-800">{{ $activity->title ?? 'Activity' }}</div>
                            <div class="text-gray-600 text-sm">{{ $activity->created_at->format('d M Y H:i') }}</div>
                            <div class="text-gray-700 mt-1">{{ $activity->description }}</div>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="text-gray-400">No activities recorded.</div>
            @endif
        </div>

        <!-- Feedbacks -->
        <div class="mb-8">
            <h3 class="text-base font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <span class="w-8 h-8 bg-gradient-to-br from-red-600 to-red-700 rounded-xl flex items-center justify-center shadow text-white">
                    <i class="fas fa-comments"></i>
                </span>
                Feedbacks
            </h3>

            @if($job->feedbacks && $job->feedbacks->count())
                <ul class="space-y-3">
                    @foreach($job->feedbacks as $feedback)
                        <li class="border-b pb-2">
                            <div class="font-semibold text-gray-800">{{ $feedback->user->name ?? 'User' }}</div>
                            <div class="text-gray-600 text-sm">{{ $feedback->created_at->format('d M Y H:i') }}</div>
                            <div class="text-gray-700 mt-1">{{ $feedback->content }}</div>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="text-gray-400">No feedbacks yet.</div>
            @endif
        </div>
        
        <!-- Tombol Feedback & Kembali -->
        @if(auth()->user()->role === 'operator' && $job->status === 'tes')
            <div class="mt-8 flex justify-between items-center">
                <!-- Tombol Give Feedback -->
                <a href="{{ route('jobs.feedback.create', ['job' => $job->id]) }}"
                    class="inline-flex items-center gap-2 px-6 py-2 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-xl font-semibold hover:from-maroon-dark hover:to-maroon-primary transition-all duration-200 shadow-lg hover:scale-105 hover:shadow-xl">
                    <i class="fas fa-comment-dots"></i>
                    Give Feedback
                </a>

                <!-- Tombol Back to Jobs -->
                <a href="{{ route('jobs.index') }}"
                    class="inline-flex items-center gap-2 px-6 py-2 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-xl font-semibold hover:from-maroon-dark hover:to-maroon-primary transition-all duration-200 shadow-lg hover:scale-105 hover:shadow-xl animate-fade-in-up"
                    style="animation-delay:0.3s;">
                    <i class="fas fa-arrow-left"></i>
                    Back to Jobs
                </a>
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
