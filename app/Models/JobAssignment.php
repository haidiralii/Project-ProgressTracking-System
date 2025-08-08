<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_id',
        'user_id',
        'role',
        'assigned_at',
    ];

    /**
     * Relasi: Penugasan ini milik 1 Job
     */
    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    /**
     * Relasi: Penugasan ini milik 1 User (Operator)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
