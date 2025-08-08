<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = [
        'project_id',
        'title',
        'description',
        'requested_at',
        'deadline',
        'status',
    ];

    protected $casts = [
        'requested_at' => 'datetime',
        'deadline' => 'datetime',
    ];

    /* ================= RELASI ================= */

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function assignments()
    {
        return $this->hasMany(JobAssignment::class);
    }

    public function operators()
    {
        return $this->belongsToMany(User::class, 'job_assignments', 'job_id', 'user_id')
            ->withPivot('role', 'assigned_at');
    }

    public function activities()
    {
        return $this->hasMany(JobActivity::class);
    }

    public function feedbacks()
    {
        return $this->hasMany(JobFeedback::class);
    }

    /* ================= ACCESSORS ================= */

    public function getStatusLabelAttribute()
    {
        $map = [
            'buat_baru'   => 'New',
            'pengerjaan'  => 'In Progress',
            'tunda'       => 'Pending',
            'tes'         => 'Testing',
            'perbaikan'   => 'Revision',
            'selesai'     => 'Completed',
        ];
        return $map[$this->status] ?? ucfirst($this->status);
    }

    public function getStatusBadgeAttribute()
    {
        $map = [
            'buat_baru'   => ['bg' => '#E0E7FF', 'text' => '#3730A3'], // indigo
            'pengerjaan'  => ['bg' => '#FEF9C3', 'text' => '#92400E'], // amber
            'tunda'       => ['bg' => '#FDE68A', 'text' => '#B45309'], // yellow
            'tes'         => ['bg' => '#E0F2FE', 'text' => '#0369A1'], // sky
            'perbaikan'   => ['bg' => '#FDE2E1', 'text' => '#B91C1C'], // rose
            'selesai'     => ['bg' => '#DCFCE7', 'text' => '#15803D'], // green
        ];
        return $map[$this->status] ?? ['bg' => '#F3F4F6', 'text' => '#374151']; // default gray
    }

    /* ================= EVENTS ================= */

    protected static function booted()
    {
        static::updating(function ($job) {
            if ($job->isDirty('status')) {
                $job->updated_at = now();
            }
        });
    }

    /* ================= HELPER ================= */

    public function setStatus($status)
    {
        $this->status = $status;
        $this->updated_at = now();
        $this->save();
    }
}
