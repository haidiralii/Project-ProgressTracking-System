<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 
        'email', 
        'password', 
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];


    /**
     * Relasi ke penugasan yang dikerjakan oleh operator
     */
    public function jobAssignments()
    {
        return $this->hasMany(JobAssignment::class);
    }

    /**
     * Relasi ke pekerjaan yang dikerjakan oleh operator
     */
    public function jobs()
    {
        return $this->belongsToMany(\App\Models\Job::class, 'job_assignments', 'user_id', 'job_id')
            ->withPivot('role', 'assigned_at')
            ->orderBy('pivot_assigned_at', 'desc');
    }

    /**
     * Operator dapat ditugaskan ke banyak job (many-to-many)
     */
    public function assignedJobs()
    {
        return $this->belongsToMany(Job::class, 'job_assignments', 'user_id', 'job_id')
            ->withPivot('role', 'assigned_at');
    }

    /**
     * Relasi ke aktivitas pengerjaan tugas
     */
    public function jobActivities()
    {
        return $this->hasMany(JobActivity::class);
    }

    /**
     * Relasi ke feedback hasil tes
     */
    public function jobFeedbacks()
    {
        return $this->hasMany(JobFeedback::class);
    }
}
