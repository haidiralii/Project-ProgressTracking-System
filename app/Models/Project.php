<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'start_date',
        'end_date',
        'status',
        'is_starred',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
        'is_starred' => 'boolean'
    ];

    public function jobs()
    {
        return $this->hasMany(Job::class);
    }

    /**
     * Status label untuk ditampilkan di UI
     */
    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            'active'      => 'Active',
            'in_progress' => 'In Progress',
            'completed'   => 'Completed',
            'tunda'       => 'On Hold',
            'perbaikan'   => 'Revision',
            default       => ucfirst($this->status),
        };
    }

    /**
     * Class badge CSS untuk status
     */
    public function getStatusBadgeAttribute()
    {
        return match ($this->status) {
            'active'      => 'active',
            'in_progress' => 'in_progress',
            'completed'   => 'completed',
            'tunda'       => 'tunda',
            'perbaikan'   => 'perbaikan',
            default       => 'active',
        };
    
    }
    /**
     * progress bar percentage
     */
   public function getProgressPercentageAttribute()
    {
        $totalJobs = $this->jobs()->count();
        if ($totalJobs === 0) {
            return 0;
        }
        // Pastikan status 'selesai' sesuai dengan database Anda
        $completedJobs = $this->jobs()->where('status', 'selesai')->count();
        return intval(round(($completedJobs / $totalJobs) * 100));
    }
}
