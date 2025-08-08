@extends('layouts.admin')

@section('title', 'Update Job')
@section('page-title', 'Update Job')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-8">
    <div class="bg-white rounded-xl shadow-md border border-gray-200 p-8 animate-fade-in-up" style="animation-delay:0.1s;">
        <h2 class="text-xl font-bold text-maroon mb-4">Update Job & Activity</h2>
        <div class="mb-6">
            <div class="mb-2"><span class="font-semibold text-gray-700">Title:</span> {{ $job->title }}</div>
            <div class="mb-2"><span class="font-semibold text-gray-700">Project:</span> {{ $job->project->name ?? '-' }}</div>
            <div class="mb-2"><span class="font-semibold text-gray-700">Requested At:</span> {{ $job->requested_at ? $job->requested_at->format('d M Y') : '-' }}</div>
            <div class="mb-2"><span class="font-semibold text-gray-700">Deadline:</span> {{ $job->deadline ? $job->deadline->format('d M Y') : '-' }}</div>
            <div class="mb-2"><span class="font-semibold text-gray-700">Current Status:</span>
                <span class="px-3 py-1 rounded-full text-xs font-semibold shadow-sm"
                      style="background-color: {{ $job->status_badge['bg'] ?? '#F3F4F6' }}; color: {{ $job->status_badge['text'] ?? '#374151' }};">
                    {{ $job->status_label ?? ucfirst($job->status) }}
                </span>
            </div>
            <div class="mb-2"><span class="font-semibold text-gray-700">Description:</span> <span class="text-gray-700">{!! nl2br(e($job->description)) ?: '<span class="text-gray-400">No description provided.</span>' !!}</span></div>
        </div>

        @if(session('error'))
            <div class="mb-6 p-4 rounded-xl bg-red-100 text-red-800 border border-red-300 shadow-sm animate-fade-in-up" style="animation-delay:0.2s;">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                {{ session('error') }}
            </div>
        @endif
        @if($errors->any())
            <div class="mb-6 p-4 rounded-xl bg-gradient-to-r from-red-100 to-rose-100 border border-red-300 shadow-sm animate-fade-in-up" style="animation-delay:0.2s;">
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-maroon rounded-full flex items-center justify-center">
                        <i class="fas fa-exclamation-triangle text-white text-sm"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-maroon font-medium">{{ $errors->first() }}</p>
                    </div>
                </div>
            </div>
        @endif

        <form action="{{ route('jobs.update_operator.submit', $job->id) }}" method="POST" class="space-y-7">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Status <span class="text-maroon">*</span></label>
                <select name="status" required
                    class="w-full px-4 py-3 rounded-xl border border-gray-300 shadow-md text-base focus:ring-2 focus:ring-maroon-primary focus:border-transparent bg-white transition-all duration-200 appearance-none cursor-pointer">
                    <option value="">Select status...</option>
                    <option value="pengerjaan" {{ old('status', $job->status)=='pengerjaan' ? 'selected' : '' }}>In Progress</option>
                    <option value="tunda" {{ old('status', $job->status)=='tunda' ? 'selected' : '' }}>Pending</option>
                    <option value="tes" {{ old('status', $job->status)=='tes' ? 'selected' : '' }}>Testing</option>
                    <option value="perbaikan" {{ old('status', $job->status)=='perbaikan' ? 'selected' : '' }}>Revision</option>
                    <option value="selesai" {{ old('status', $job->status)=='selesai' ? 'selected' : '' }}>Completed</option>
                </select>
                @error('status')
                    <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
                @enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Activity Note</label>
                <textarea name="activity_note" rows="3"
                    class="w-full px-4 py-3 rounded-xl border border-gray-300 shadow-md text-base focus:ring-2 focus:ring-maroon-primary focus:border-transparent bg-white transition-all duration-200 resize-y"
                    placeholder="Describe your activity...">{{ old('activity_note') }}</textarea>
                @error('activity_note')
                    <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
                @enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Activity Date <span class="text-maroon">*</span></label>
                <input type="date" name="activity_date" required
                    class="w-full px-4 py-3 rounded-xl border border-gray-300 shadow-md text-base focus:ring-2 focus:ring-maroon-primary focus:border-transparent bg-white transition-all duration-200"
                    value="{{ old('activity_date', now()->format('Y-m-d')) }}">
                @error('activity_date')
                    <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100 mt-8">
                <a href="{{ route('jobs.index') }}"
                   class="inline-flex items-center gap-2 px-5 py-2 bg-gray-100 text-gray-700 rounded-xl font-semibold hover:bg-gray-200 transition-all duration-200 shadow border border-gray-200">
                    <i class="fas fa-arrow-left"></i>
                    Cancel
                </a>
                <button type="submit"
                    class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-red-700 to-red-600 text-white rounded-xl font-semibold hover:from-maroon-dark hover:to-maroon-primary transition-all duration-200 shadow-lg hover:scale-105 hover:shadow-xl">
                    <i class="fas fa-save"></i>
                    Save Update
                </button>
            </div>
        </form>
    </div>
</div>

<style>
:root {
    --maroon-primary: #CA2626;
    --maroon-light: #E04B4B;
    --maroon-dark: #9B1A1A;
}
.text-maroon { color: var(--maroon-primary); }
.bg-maroon { background-color: var(--maroon-primary); }
.bg-maroon-light { background-color: var(--maroon-light); }
.bg-maroon-dark { background-color: var(--maroon-dark); }
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