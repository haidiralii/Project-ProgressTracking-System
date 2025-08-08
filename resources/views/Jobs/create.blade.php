@extends('layouts.admin')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-8">
    <div class="bg-white rounded-xl shadow-md border border-gray-200 p-8 animate-fade-in-up" style="animation-delay:0.1s;">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-lg font-semibold text-red-700 flex items-center gap-2">
                    <span class="w-10 h-10 bg-gradient-to-br from-red-600 to-red-700 rounded-xl flex items-center justify-center shadow text-white">
                        <i class="fas fa-tasks"></i>
                    </span>
                    Add New Job
                </h1>
                <p class="text-sm text-gray-500 mt-1">Create a new job for your project</p>
            </div>
            <a href="{{ route('projects.show', $project->id) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-maroon rounded-xl font-semibold hover:bg-maroon-lighter transition-all duration-200 shadow border border-maroon-light">
                <i class="fas fa-arrow-left"></i>
                Back
            </a>
        </div>

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

        <form action="{{ route('jobs.store') }}" method="POST" class="space-y-8">
            @csrf
            <input type="hidden" name="project_id" value="{{ $project->id }}">
            <!-- Job Information Section -->
            <div>
                <div class="flex items-center gap-3 mb-6">
                    <span class="w-10 h-10 bg-gradient-to-br from-red-600 to-red-700 rounded-xl flex items-center justify-center shadow text-white">
                        <i class="fas fa-info-circle"></i>
                    </span>
                    <div>
                        <h3 class="text-base font-semibold text-gray-900">Job Information</h3>
                        <p class="text-xs text-gray-500">Main details of the job</p>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="flex flex-col mb-4">
                        <label for="title" class="text-sm font-semibold text-gray-700 mb-2">Job Title <span class="text-maroon">*</span></label>
                        <input type="text" id="title" name="title" required
                            class="rounded-md border border-gray-300 px-4 py-3 text-base focus:ring-2 focus:ring-maroon-primary focus:border-transparent bg-white transition-all duration-200"
                            placeholder="Enter job title..." value="{{ old('title') }}">
                    </div>
                    <div class="col-span-1 md:col-span-2 flex flex-col mb-4">
                        <label for="description" class="text-sm font-semibold text-gray-700 mb-2">Description</label>
                        <textarea id="description" name="description" rows="4"
                            class="rounded-md border border-gray-300 px-4 py-3 text-base focus:ring-2 focus:ring-maroon-primary focus:border-transparent bg-white transition-all duration-200 resize-y"
                            placeholder="Describe the job details...">{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>
            <!-- Assign Operators Section -->
            <div>
                <div class="flex items-center gap-3 mb-6">
                    <span class="w-10 h-10 bg-gradient-to-br from-red-600 to-red-700 rounded-xl flex items-center justify-center shadow text-white">
                        <i class="fas fa-users"></i>
                    </span>
                    <div>
                        <h3 class="text-base font-semibold text-gray-900">Assign Operators</h3>
                        <p class="text-xs text-gray-500">Select operators for this job</p>
                    </div>
                </div>
                <div class="bg-gray-50 rounded-lg border border-gray-200 p-4 flex flex-wrap gap-4">
                    @foreach($operators as $operator)
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="operator_ids[]" value="{{ $operator->id }}" class="form-checkbox h-5 w-5 text-maroon rounded focus:ring-maroon-primary">
                            <span class="text-gray-800 font-medium">{{ $operator->name }}</span>
                            <span class="text-xs text-gray-500">({{ $operator->email }})</span>
                        </label>
                    @endforeach
                </div>
                <small class="text-gray-500">Klik untuk memilih operator yang akan ditugaskan.</small>
            </div>
            <!-- Job Timeline Section -->
            <div>
                <div class="flex items-center gap-3 mb-6">
                    <span class="w-10 h-10 bg-gradient-to-br from-red-600 to-red-700 rounded-xl flex items-center justify-center shadow text-white">
                        <i class="fas fa-calendar-alt"></i>
                    </span>
                    <div>
                        <h3 class="text-base font-semibold text-gray-900">Job Timeline</h3>
                        <p class="text-xs text-gray-500">Request date and job deadline</p>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="flex flex-col mb-4">
                        <label for="requested_at" class="text-sm font-semibold text-gray-700 mb-2">Request Date <span class="text-maroon">*</span></label>
                        <input type="date" id="requested_at" name="requested_at" required
                            class="rounded-md border border-gray-300 px-4 py-3 text-base focus:ring-2 focus:ring-maroon-primary focus:border-transparent bg-white transition-all duration-200"
                            value="{{ old('requested_at') }}">
                    </div>
                    <div class="flex flex-col mb-4">
                        <label for="deadline" class="text-sm font-semibold text-gray-700 mb-2">Deadline</label>
                        <input type="date" id="deadline" name="deadline"
                            class="rounded-md border border-gray-300 px-4 py-3 text-base focus:ring-2 focus:ring-maroon-primary focus:border-transparent bg-white transition-all duration-200"
                            value="{{ old('deadline') }}">
                    </div>
                </div>
            </div>
            <!-- Job Status Section -->
            <div>
                <div class="flex items-center gap-3 mb-6">
                    <span class="w-10 h-10 bg-gradient-to-br from-red-600 to-red-700 rounded-xl flex items-center justify-center shadow text-white">
                        <i class="fas fa-check-circle"></i>
                    </span>
                    <div>
                        <h3 class="text-base font-semibold text-gray-900">Job Status</h3>
                        <p class="text-xs text-gray-500">Select the current job status</p>
                    </div>
                </div>
                <div class="flex flex-col mb-4">
                    <label for="status" class="text-sm font-semibold text-gray-700 mb-2">Status <span class="text-maroon">*</span></label>
                    <select name="status" class="form-select" required>
                        <option value="">Select Status...</option>
                        <option value="buat_baru" {{ old('status', $job->status ?? '')=='buat_baru' ? 'selected' : '' }}>New</option>
                        <option value="pengerjaan" {{ old('status', $job->status ?? '')=='pengerjaan' ? 'selected' : '' }}>In Progress</option>
                        <option value="tunda" {{ old('status', $job->status ?? '')=='tunda' ? 'selected' : '' }}>Pending</option>
                        <option value="tes" {{ old('status', $job->status ?? '')=='tes' ? 'selected' : '' }}>Testing</option>
                        <option value="perbaikan" {{ old('status', $job->status ?? '')=='perbaikan' ? 'selected' : '' }}>Revision</option>
                        <option value="selesai" {{ old('status', $job->status ?? '')=='selesai' ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>
            </div>
            <!-- Form Actions -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100 mt-8">
                <button type="button" onclick="window.history.back()" class="inline-flex items-center gap-2 px-5 py-2 bg-gray-100 text-gray-700 rounded-xl font-semibold hover:bg-gray-200 transition-all duration-200 shadow border border-gray-200">
                    <i class="fas fa-arrow-left"></i>
                    Cancel
                </button>
                <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-xl font-semibold hover:from-maroon-dark hover:to-maroon-primary transition-all duration-200 shadow-lg hover:scale-105 hover:shadow-xl">
                    <i class="fas fa-save"></i>
                    Save Job
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
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-resize textarea
    const textarea = document.querySelector('.form-textarea');
    if (textarea) {
        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = this.scrollHeight + 'px';
        });
    }
    // Form validation
    const form = document.querySelector('.project-form');
    if (form) {
        form.addEventListener('submit', function(e) {
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.style.borderColor = 'var(--primary-red)';
                } else {
                    field.style.borderColor = 'var(--border-color)';
                }
            });
            if (!isValid) {
                e.preventDefault();
                alert('Please fill in all required fields.');
            }
        });
    }
});
</script>
@endsection