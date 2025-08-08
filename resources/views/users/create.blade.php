@extends('layouts.admin')

@section('title', 'Add New User')
@section('page-title', 'Add User')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-8">
    <div class="bg-white rounded-xl shadow-md border border-gray-200 p-8 animate-fade-in-up" style="animation-delay:0.1s;">
        <!-- Header -->
        <div class="flex items-center gap-4 mb-8">
            <span class="w-12 h-12 bg-gradient-to-br from-red-600 to-red-700 rounded-xl flex items-center justify-center shadow text-white">
                <i class="fas fa-user-plus text-2xl"></i>
            </span>
            <div>
                <h2 class="text-xl font-semibold text-maroon mb-1">Add New User</h2>
                <p class="text-gray-500 text-sm">Fill in the form below to create a new user account.</p>
            </div>
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

        <form action="{{ route('users.store') }}" method="POST" class="space-y-7">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Name <span class="text-maroon">*</span></label>
                <input type="text" name="name" required
                    class="w-full px-4 py-3 rounded-xl border border-gray-300 shadow-md text-base focus:ring-2 focus:ring-maroon-primary focus:border-transparent bg-white transition-all duration-200"
                    placeholder="Enter name..." value="{{ old('name') }}">
                @error('name')
                    <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
                @enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Email <span class="text-maroon">*</span></label>
                <input type="email" name="email" required
                    class="w-full px-4 py-3 rounded-xl border border-gray-300 shadow-md text-base focus:ring-2 focus:ring-maroon-primary focus:border-transparent bg-white transition-all duration-200"
                    placeholder="Enter email..." value="{{ old('email') }}">
                @error('email')
                    <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
                @enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Password <span class="text-maroon">*</span></label>
                <input type="password" name="password" required
                    class="w-full px-4 py-3 rounded-xl border border-gray-300 shadow-md text-base focus:ring-2 focus:ring-maroon-primary focus:border-transparent bg-white transition-all duration-200"
                    placeholder="Enter password...">
                @error('password')
                    <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
                @enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Role <span class="text-maroon">*</span></label>
                <select name="role" required
                    class="w-full px-4 py-3 rounded-xl border border-gray-300 shadow-md text-base focus:ring-2 focus:ring-maroon-primary focus:border-transparent bg-white transition-all duration-200 appearance-none cursor-pointer">
                    <option value="">Select role...</option>
                    <option value="operator" {{ old('role') == 'operator' ? 'selected' : '' }}>Operator</option>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="director" {{ old('role') == 'director' ? 'selected' : '' }}>Director</option>
                </select>
                @error('role')
                    <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100 mt-8">
                <a href="{{ route('users.index') }}"
                   class="inline-flex items-center gap-2 px-5 py-2 bg-gray-100 text-gray-700 rounded-xl font-semibold hover:bg-gray-200 transition-all duration-200 shadow border border-gray-200">
                    <i class="fas fa-arrow-left"></i>
                    Cancel
                </a>
                <button type="submit"
                    class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-xl font-semibold hover:from-maroon-dark hover:to-maroon-primary transition-all duration-200 shadow-lg hover:scale-105 hover:shadow-xl">
                    <i class="fas fa-save"></i>
                    Save User
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
    to { opacity: 1; transform: translateY(0); }
}
</style>
@endsection
