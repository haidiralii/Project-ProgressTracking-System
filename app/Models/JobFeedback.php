<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobFeedback extends Model
{
    use HasFactory;
    protected $table = 'job_feedbacks';
    protected $fillable = [
        'job_id',
        'user_id',
        'feedback_note',
        'feedback_date',
    ];

    /**
     * Relasi: Feedback ini untuk 1 Job
     */
    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    /**
     * Relasi: Feedback ini diberikan oleh 1 User (tester)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
