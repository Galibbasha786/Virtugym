<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class ExerciseLog extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'exercise_logs';
    
    protected $fillable = [
        'user_id', 'workout_id', 'exercise_id', 'exercise_name',
        'sets', 'reps', 'weight', 'rpe', 'rest_time_seconds',
        'notes', 'is_pr'
    ];
    
    protected $casts = [
        'sets' => 'integer',
        'reps' => 'array',
        'weight' => 'array',
        'rpe' => 'integer',
        'is_pr' => 'boolean'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function workout()
    {
        return $this->belongsTo(Workout::class);
    }
}