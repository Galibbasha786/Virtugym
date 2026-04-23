<?php

namespace App\Models;

use MongoDB\Laravel\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;
    
    protected $connection = 'mongodb';
    protected $collection = 'users';
    
    protected $fillable = [
        'name', 'email', 'password',
        'age', 'gender', 'weight', 'height',
        'fitness_level', 'goal', 'equipment', 
        'workout_days', 'workout_duration', 'injuries'
    ];
    
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    protected $casts = [
        'email_verified_at' => 'datetime',
        'equipment' => 'array',
        'age' => 'integer',
        'weight' => 'float',
        'height' => 'float',
        'workout_days' => 'integer',
        'workout_duration' => 'integer',
    ];
    
    public function workouts()
    {
        return $this->hasMany(Workout::class);
    }
    
    public function progressMetrics()
    {
        return $this->hasMany(ProgressMetric::class);
    }
}