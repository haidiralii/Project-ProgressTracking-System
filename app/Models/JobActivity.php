<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_id',
        'user_id',
        'status',
        'activity_note',
        'activity_date',
    ];

    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Status label mengikuti mapping di Job model jika ada.
     */
    public function getStatusLabelAttribute()
    {
        if ($this->job) {
            // pakai accessor Job
            $map = [
                'buat_baru'   => 'New',
                'pengerjaan'  => 'In Progress',
                'tunda'       => 'Pending',
                'tes'         => 'Testing',
                'perbaikan'   => 'Revision',
                'selesai'     => 'Completed',
            ];
            return $map[$this->status] ?? ucfirst(str_replace('_', ' ', $this->status));
        }

        return $this->status
            ? ucfirst(str_replace('_', ' ', $this->status))
            : '-';
    }

    /**
     * Warna badge mengikuti mapping di Job model jika ada.
     */
    public function getStatusBadgeAttribute()
    {
        if ($this->job) {
            // mapping warna sama persis dengan Job
            $map = [
                'buat_baru'   => ['bg' => '#E0E7FF', 'text' => '#3730A3'], // indigo
                'pengerjaan'  => ['bg' => '#FEF9C3', 'text' => '#92400E'], // amber
                'tunda'       => ['bg' => '#FDE68A', 'text' => '#B45309'], // yellow
                'tes'         => ['bg' => '#E0F2FE', 'text' => '#0369A1'], // sky
                'perbaikan'   => ['bg' => '#FDE2E1', 'text' => '#B91C1C'], // rose
                'selesai'     => ['bg' => '#DCFCE7', 'text' => '#15803D'], // green
            ];
            return $map[$this->status] ?? ['bg' => '#F3F4F6', 'text' => '#374151'];
        }

        // default kalau tidak ada relasi job
        return ['bg' => '#E5E7EB', 'text' => '#374151'];
    }
}
