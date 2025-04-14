<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'time_limit',
        'passing_score',
        'instructions',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'time_limit' => 'integer',
        'passing_score' => 'integer',
    ];

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function attempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }
}
