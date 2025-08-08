@extends('layouts.admin')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-8">
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8 animate-fade-in">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-lg font-semibold text-red-700 flex items-center gap-2">
                    <span class="w-10 h-10 bg-gradient-to-br from-red-600 to-red-700 rounded-xl flex items-center justify-center shadow text-white">
                        <i class="fas fa-edit"></i>
                    </span>
                    Edit Project
                </h1>
                <p class="text-sm text-gray-500 mt-1">Update project information</p>
            </div>
            <a href="{{ route('projects.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-red-700 rounded-xl font-semibold hover:bg-red-50 transition-all duration-200 shadow border border-red-200">
                <i class="fas fa-arrow-left"></i>
                Back
            </a>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 rounded-xl bg-gradient-to-r from-red-50 to-rose-50 border border-red-200 shadow-sm animate-fade-in">
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-red-600 rounded-full flex items-center justify-center">
                        <i class="fas fa-check text-white text-sm"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-red-800 font-medium">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 p-4 rounded-xl bg-gradient-to-r from-red-100 to-rose-100 border border-red-300 shadow-sm animate-fade-in">
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-red-600 rounded-full flex items-center justify-center">
                        <i class="fas fa-exclamation-triangle text-white text-sm"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-red-800 font-medium">{{ $errors->first() }}</p>
                    </div>
                </div>
            </div>
        @endif

        <form action="{{ route('projects.update', $project->id) }}" method="POST" class="space-y-8">
            @csrf
            @method('PUT')
            <!-- Project Information Section -->
            <div>
                <div class="flex items-center gap-3 mb-6">
                    <span class="w-10 h-10 bg-gradient-to-br from-red-600 to-red-700 rounded-xl flex items-center justify-center shadow text-white">
                        <i class="fas fa-info-circle"></i>
                    </span>
                    <div>
                        <h3 class="text-base font-semibold text-gray-900">Project Information</h3>
                        <p class="text-xs text-gray-500">Main details of the project</p>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="flex flex-col mb-4">
                        <label for="name" class="text-sm font-medium text-gray-700 mb-2">Project Name <span class="text-red-600">*</span></label>
                        <input type="text" id="name" name="name" value="{{ old('name', $project->name) }}" required
                            class="rounded-md border border-gray-300 px-4 py-3 text-base focus:ring-2 focus:ring-red-500 focus:border-transparent bg-white transition-all duration-200" placeholder="Enter project name...">
                    </div>
                    <div class="col-span-1 md:col-span-2 flex flex-col mb-4">
                        <label for="description" class="text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea id="description" name="description" rows="4"
                            class="rounded-md border border-gray-300 px-4 py-3 text-base focus:ring-2 focus:ring-red-500 focus:border-transparent bg-white transition-all duration-200 resize-y" placeholder="Describe the project...">{{ old('description', $project->description) }}</textarea>
                    </div>
                    <div class="flex flex-col mb-4">
                        <label for="status" class="text-sm font-medium text-gray-700 mb-2">Status <span class="text-red-600">*</span></label>
                        <select id="status" name="status" required
                            class="rounded-md border border-gray-300 px-4 py-3 text-base focus:ring-2 focus:ring-red-500 focus:border-transparent bg-white transition-all duration-200">
                            <option value="">Select status</option>
                            <option value="active" {{ old('status', $project->status) == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="in_progress" {{ old('status', $project->status) == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="completed" {{ old('status', $project->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                    </div>
                </div>
            </div>
            <!-- Project Timeline Section -->
            <div>
                <div class="flex items-center gap-3 mb-6">
                    <span class="w-10 h-10 bg-gradient-to-br from-red-600 to-red-700 rounded-xl flex items-center justify-center shadow text-white">
                        <i class="fas fa-calendar-alt"></i>
                    </span>
                    <div>
                        <h3 class="text-base font-semibold text-gray-900">Project Timeline</h3>
                        <p class="text-xs text-gray-500">Start date and deadline</p>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="flex flex-col mb-4">
                        <label for="start_date" class="text-sm font-medium text-gray-700 mb-2">Start Date <span class="text-red-600">*</span></label>
                        <input type="date" id="start_date" name="start_date" value="{{ old('start_date', $project->start_date ? $project->start_date->format('Y-m-d') : '') }}" required
                            class="rounded-md border border-gray-300 px-4 py-3 text-base focus:ring-2 focus:ring-red-500 focus:border-transparent bg-white transition-all duration-200">
                    </div>
                    <div class="flex flex-col mb-4">
                        <label for="end_date" class="text-sm font-medium text-gray-700 mb-2">End Date</label>
                        <input type="date" id="end_date" name="end_date" value="{{ old('end_date', $project->end_date ? $project->end_date->format('Y-m-d') : '') }}"
                            class="rounded-md border border-gray-300 px-4 py-3 text-base focus:ring-2 focus:ring-red-500 focus:border-transparent bg-white transition-all duration-200">
                    </div>
                </div>
            </div>
            <!-- Actions -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100 mt-8">
                <a href="{{ route('projects.index') }}" class="inline-flex items-center gap-2 px-5 py-2 bg-gray-100 text-gray-700 rounded-xl font-semibold hover:bg-gray-200 transition-all duration-200 shadow border border-gray-200">
                    <i class="fas fa-arrow-left"></i>
                    Cancel
                </a>
                <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-xl font-semibold hover:from-red-700 hover:to-red-800 transition-all duration-200 shadow-lg hover:shadow-xl">
                    <i class="fas fa-save"></i>
                    Update Project
                </button>
            </div>
        </form>
    </div>
</div>

<style>
.animate-fade-in {
    animation: fadeIn 0.6s cubic-bezier(0.4, 0, 0.2, 1) forwards;
    opacity: 0;
}
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px);}
    to { opacity: 1; transform: translateY(0);}
}
</style>
@endsection