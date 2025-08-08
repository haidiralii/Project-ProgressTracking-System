@extends('layouts.admin')

@section('title', 'Feedback')

@section('content')
<style>
    .animate-fade-in-up {
        opacity: 0;
        animation: fadeInUp 0.6s cubic-bezier(0.4,0,0.2,1) forwards;
    }
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(16px);}
        to { opacity: 1; transform: translateY(0);}
    }
</style>
<div class="max-w-xl mx-auto mt-10 bg-white rounded-2xl shadow-lg border border-gray-100 p-8 animate-fade-in-up">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center gap-3 mb-2">
            <span class="w-10 h-10 bg-gradient-to-br from-red-600 to-red-700 rounded-xl flex items-center justify-center shadow text-white">
                <i class="fas fa-comment-dots"></i>
            </span>
            <div>
                <h1 class="text-lg font-bold text-red-700">Submit Feedback After Testing for This Job</h1>
                <p class="text-sm text-gray-500 mt-1">Provide constructive input to help the assigned operator improve their work.</p>
            </div>
        </div>
    </div>

    <!-- Job Info -->
    <div class="bg-gray-50 rounded-lg border border-gray-200 p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <div>
                <span class="font-semibold text-gray-700 flex items-center gap-2">
                    <i class="fas fa-briefcase text-red-600"></i> Job Title:
                </span>
                <span class="ml-1 text-gray-900">{{ $job->title }}</span>
            </div>
            <div>
                <span class="font-semibold text-gray-700 flex items-center gap-2">
                    <i class="fas fa-folder-open text-red-600"></i> Project:
                </span>
                <span class="ml-1 text-gray-900">{{ $job->project->name ?? '-' }}</span>
            </div>
            <div>
                <span class="font-semibold text-gray-700 flex items-center gap-2">
                    <i class="fas fa-user text-red-600"></i> Assigned Operator:
                </span>
                <span class="ml-1 text-gray-900">
                    @if($job->operators && $job->operators->count())
                        {{ $job->operators->pluck('name')->join(', ') }}
                    @else
                        -
                    @endif
                </span>
            </div>
            <div>
                <span class="font-semibold text-gray-700 flex items-center gap-2">
                    <i class="fas fa-calendar-alt text-red-600"></i> Deadline:
                </span>
                <span class="ml-1 text-gray-900">
                    {{ $job->deadline ? \Carbon\Carbon::parse($job->deadline)->format('M d, Y') : '-' }}
                </span>
            </div>
        </div>
    </div>

    <!-- Feedback Form -->
    <form method="POST" action="{{ route('jobs.feedback.store', $job->id) }}" class="space-y-6">
        @csrf
        <div>
            <label for="feedback" class="block text-sm font-semibold text-gray-700 mb-2">
                <i class="fas fa-pen-nib mr-1 text-red-600"></i> Your Feedback
            </label>
            <textarea name="feedback" id="feedback" rows="6"
                class="w-full rounded-md border border-gray-300 px-4 py-3 text-base focus:ring-2 focus:ring-red-600 focus:border-transparent bg-white transition-all duration-200 resize-y placeholder:text-gray-400"
                placeholder="Write your feedback here..." required>{{ old('feedback') }}</textarea>
            @error('feedback')
                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
            @enderror
        </div>
        <div class="flex flex-col md:flex-row items-center justify-end gap-3 pt-4 border-t border-gray-100 mt-4">
            <a href="{{ route('jobs.show', $job->id) }}"
               class="inline-flex items-center gap-2 px-5 py-2 bg-gray-100 text-gray-700 rounded-xl font-semibold hover:bg-gray-200 transition-all duration-200 shadow border border-gray-200">
                <i class="fas fa-arrow-left"></i>
                Back to Job
            </a>
            <button type="submit"
                class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-xl font-semibold hover:from-maroon-dark hover:to-maroon-primary transition-all duration-200 shadow-lg hover:scale-105 hover:shadow-xl">
                <i class="fas fa-paper-plane"></i>
                Submit Feedback
            </button>
        </div>
    </form>
</div>
@endsection