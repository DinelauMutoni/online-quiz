<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'quiz_id',
        'start_time',
        'end_time',
        'score',
        'status'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'score' => 'float'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    public function getIsCompletedAttribute()
    {
        return $this->status === 'completed';
    }

    public function getIsInProgressAttribute()
    {
        return $this->status === 'in_progress';
    }

    public function getHasPassedAttribute()
    {
        return $this->is_completed && $this->score >= $this->quiz->passing_score;
    }
}
