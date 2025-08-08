{{-- resources/views/activities/edit.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-2xl font-bold mb-4">Edit Time Log</h1>

    <form action="{{ route('activities.update', $activity->id) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block font-semibold">Job</label>
            <select name="job_id" class="w-full border rounded px-3 py-2">
                @foreach($jobs as $job)
                    <option value="{{ $job->id }}" {{ $job->id == $activity->job_id ? 'selected' : '' }}>
                        {{ $job->title }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block font-semibold">User</label>
            <select name="user_id" class="w-full border rounded px-3 py-2">
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ $user->id == $activity->user_id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block font-semibold">Status</label>
            <select name="status" class="w-full border rounded px-3 py-2">
                @foreach(['buat_baru', 'pengerjaan', 'tunda', 'tes', 'perbaikan', 'selesai'] as $status)
                    <option value="{{ $status }}" {{ $activity->status == $status ? 'selected' : '' }}>
                        {{ ucfirst($status) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block font-semibold">Catatan</label>
            <textarea name="activity_note" class="w-full border rounded px-3 py-2">{{ $activity->activity_note }}</textarea>
        </div>

        <div>
            <label class="block font-semibold">Tanggal Aktivitas</label>
            <input type="date" name="activity_date" class="w-full border rounded px-3 py-2"
                   value="{{ \Carbon\Carbon::parse($activity->activity_date)->format('Y-m-d') }}">
        </div>

        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Perbarui</button>
        <a href="{{ route('activities.index') }}" class="ml-2 text-gray-600 hover:underline">Batal</a>
    </form>
</div>
@endsection
