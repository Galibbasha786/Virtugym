<?php

namespace App\Http\Controllers;

use App\Models\Workout;
use App\Models\ProgressMetric;
use App\Models\ExerciseLog;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Stats
        $totalWorkouts = Workout::where('user_id', $user->id)->count();
        $completedWorkouts = Workout::where('user_id', $user->id)
            ->whereNotNull('completed_at')
            ->count();
        
        $totalExercisesLogged = ExerciseLog::where('user_id', $user->id)->count();
        $totalVolume = ExerciseLog::where('user_id', $user->id)->sum('weight');
        
        // Recent workouts
        $recentWorkouts = Workout::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Latest progress
        $latestProgress = ProgressMetric::where('user_id', $user->id)
            ->orderBy('date', 'desc')
            ->first();
        
        // Personal Records
        $prs = ExerciseLog::where('user_id', $user->id)
            ->where('is_pr', true)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Calculate streak
        $streak = $this->calculateStreak($user->id);
        
        return view('dashboard', compact(
            'totalWorkouts', 'completedWorkouts', 
            'totalExercisesLogged', 'totalVolume',
            'recentWorkouts', 'latestProgress', 
            'prs', 'streak'
        ));
    }
    
    private function calculateStreak($userId)
    {
        $workouts = Workout::where('user_id', $userId)
            ->whereNotNull('completed_at')
            ->orderBy('completed_at', 'desc')
            ->get();
        
        if ($workouts->isEmpty()) {
            return 0;
        }
        
        $streak = 1;
        $lastDate = $workouts->first()->completed_at->format('Y-m-d');
        
        foreach ($workouts->slice(1) as $workout) {
            $currentDate = $workout->completed_at->format('Y-m-d');
            $expectedDate = date('Y-m-d', strtotime($lastDate . ' -1 day'));
            
            if ($currentDate === $expectedDate) {
                $streak++;
                $lastDate = $currentDate;
            } elseif ($currentDate !== $lastDate) {
                break;
            }
        }
        
        return $streak;
    }
}