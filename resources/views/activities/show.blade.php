@extends('layouts.admin')

@section('content')
<div class="max-w-xl mx-auto px-4 py-8">
    <div class="bg-white rounded-xl shadow-lg mb-6 p-6 flex items-center gap-4 border border-gray-200 animate-fade-in-up" style="animation-delay:0.1s;">
        <div class="w-12 h-12 flex items-center justify-center rounded-lg bg-gradient-to-br from-red-600 to-red-700 shadow">
            <i class="fas fa-tasks text-white text-2xl"></i>
        </div>
        <div>
            <h2 class="text-2xl font-bold text-maroon mb-1">Activity Detail</h2>
            <p class="text-gray-500 text-sm">Operator activity log</p>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200 animate-fade-in-up" style="animation-delay:0.2s;">
        <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <dt class="font-bold text-gray-700">Operator</dt>
                <dd class="text-maroon font-semibold">{{ $activity->user->name ?? '-' }}</dd>
            </div>
            <div>
                <dt class="font-bold text-gray-700">Activity Date</dt>
                <dd class="text-gray-900 flex items-center gap-2">
                    <span class="inline-block w-7 h-7 rounded-full bg-gradient-to-br from-red-600 to-red-700 flex items-center justify-center text-white">
                        <i class="fas fa-clock"></i>
                    </span>
                    {{ \Carbon\Carbon::parse($activity->activity_date)->format('d M Y') }}
                </dd>
            </div>
            <div class="md:col-span-2">
                <dt class="font-bold text-gray-700 mb-2">Catatan Aktivitas</dt>
                <dd class="text-gray-900 whitespace-pre-line bg-gray-50 rounded-lg p-4 border border-gray-100">{{ $activity->activity_note ?? '-' }}</dd>
            </div>
            <div class="md:col-span-2 mt-4">
                <a href="{{ route('activities.index') }}" class="inline-block px-5 py-2 rounded-xl bg-gradient-to-r from-red-600 to-red-700 text-white font-semibold hover:scale-105 hover:shadow-lg transition-all duration-200">
                    <i class="fas fa-arrow-left"></i> Back to Activities
                </a>
            </div>
        </dl>
    </div>
</div>
<style>
:root {
    --maroon-primary: #CA2626;
    --maroon-light: #E04B4B;
}
.text-maroon { color: var(--maroon-primary); }
.bg-maroon { background-color: var(--maroon-primary); }
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
