<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Workout extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'workouts';
    
    protected $fillable = [
        'user_id', 'trainer_id', 'trainee_id', 'title', 'type', 'difficulty', 'duration_minutes',
        'exercises', 'scheduled_date', 'completed_at', 'notes',
        'total_volume', 'total_reps', 'rating', 'assigned_by'
    ];
    
    protected $casts = [
        'exercises' => 'array',
        'scheduled_date' => 'datetime',
        'completed_at' => 'datetime',
        'total_volume' => 'float',
        'total_reps' => 'integer',
        'rating' => 'integer'
    ];
    
    public function trainee()
    {
        return $this->belongsTo(User::class, 'trainee_id');
    }
    
    public function trainer()
    {
        return $this->belongsTo(User::class, 'trainer_id');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}