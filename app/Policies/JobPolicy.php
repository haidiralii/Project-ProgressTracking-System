<?php

namespace App\Policies;

use App\Models\Job;
use App\Models\User;

class JobPolicy
{
    /**
     * Determine whether the user can view any jobs (for jobs.index).
     */
    public function viewAny(User $user)
    {
        // Admin dan operator bisa akses halaman jobs
        return in_array($user->role, ['admin', 'operator']);
    }
    /**
     * Determine if the given job can be viewed by the user.
     */
    public function view(User $user, Job $job)
    {
        // Admin bisa akses semua
        if ($user->role === 'admin') return true;
        // Operator yang di-assign ke job bisa akses
        return $job->operators->contains('id', $user->id);
    }

    /**
     * Determine if the given job can be updated by the user.
     */
    public function update(User $user, Job $job)
    {
        // Admin bisa update semua
        if ($user->role === 'admin') return true;
        // Operator yang di-assign ke job bisa update
        return $job->operators->contains('id', $user->id);
    }
}
