@extends('layouts.admin')

@section('content')
<div class="w-full">

    <!-- Filter/Search Card -->
    <div class="w-full">
        <form action="{{ route('users.index') }}" method="GET" class="bg-white/80 backdrop-blur-sm p-6 rounded-2xl shadow-xl border border-white/50 animate-fade-in mx-auto mt-0 mb-8 lg:max-w-[98%] lg:mx-auto" style="animation-delay:0.2s;">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-red-700 flex items-center gap-2">
                    <i class="fas fa-filter text-red-600"></i>
                    User Filters
                </h3>
                <div class="flex gap-2">
                    <a href="{{ route('users.create') }}" class="inline-flex items-center gap-2 px-6 py-2.5 bg-red-600 text-white rounded-xl font-semibold hover:bg-red-700 transition-all duration-200 shadow hover:shadow-lg">
                        <i class="fas fa-plus"></i>
                        Add User
                    </a>
                    <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-red-600 text-white rounded-xl font-semibold hover:bg-red-700 transition-all duration-200 shadow hover:shadow-lg">
                        <i class="fas fa-search"></i>
                        Apply Filter
                    </button>
                    <a href="{{ route('users.index') }}" class="inline-flex items-center gap-2 px-6 py-2.5 bg-gray-100 text-red-700 rounded-xl font-semibold hover:bg-red-50 transition-all duration-200 shadow border border-red-200">
                        <i class="fas fa-sync"></i>
                        Reset
                    </a>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="space-y-2">
                    <label class="text-sm font-medium text-gray-700">Search Users</label>
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Find Users..." class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent bg-white transition-all duration-200" />
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-medium text-gray-700">Role</label>
                    <select name="role" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent bg-white transition-all duration-200">
                        <option value="">All Roles</option>
                        <option value="admin" {{ request('role')=='admin' ? 'selected' : '' }}>Admin</option>
                        <option value="operator" {{ request('role')=='operator' ? 'selected' : '' }}>Operator</option>
                        <option value="director" {{ request('role')=='director' ? 'selected' : '' }}>Director</option>
                    </select>
                </div>
            </div>
        </form>
    </div>

    <!-- Users Table Card -->
    <div class="w-full">
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/50 overflow-hidden animate-fade-in mx-auto mt-0 lg:max-w-[98%] lg:mx-auto" style="animation-delay:0.4s;">
            <div class="bg-gradient-to-r from-red-700 to-red-900 px-6 py-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-white">Users Overview</h3>
                    <div class="text-sm text-red-100">{{ $users->count() }} users found</div>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full min-w-[700px]">
                    <thead>
                        <tr class="bg-gradient-to-r from-red-50 to-red-100 border-b border-red-200">
                            <th class="py-4 px-6 font-bold text-red-700 text-left">Name</th>
                            <th class="py-4 px-6 font-bold text-red-700 text-left">Email</th>
                            <th class="py-4 px-6 font-bold text-red-700 text-left">Role</th>
                            <th class="py-4 px-6 font-bold text-red-700 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse ($users as $user)
                            <tr class="hover:bg-red-50 transition-all duration-200 group animate-slide-up" style="animation-delay: {{ 0.5 + $loop->index * 0.05 }}s;">
                                <td class="py-4 px-6 font-semibold text-gray-900">{{ $user->name }}</td>
                                <td class="py-4 px-6 text-gray-700">{{ $user->email }}</td>
                                <td class="py-4 px-6">
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold
                                        @if($user->role == 'admin') bg-gradient-to-r from-red-100 to-red-200 text-red-700 border border-red-200
                                        @elseif($user->role == 'operator') bg-gradient-to-r from-blue-100 to-blue-200 text-blue-700 border border-blue-200
                                        @elseif($user->role == 'director') bg-gradient-to-r from-yellow-100 to-yellow-200 text-yellow-700 border border-yellow-200
                                        @else bg-gray-100 text-gray-700 border border-gray-200
                                        @endif">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <a href="{{ route('users.show', $user->id) }}" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-gray-100 text-red-700 hover:bg-red-700 hover:text-white font-medium text-xs transition-all duration-200 border border-red-200 hover:border-red-300">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-6 text-center text-gray-500">No users found.</td>
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
