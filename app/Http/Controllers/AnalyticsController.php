<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Workout;
use App\Models\User;
use App\Models\ExerciseLog;
use Illuminate\Support\Facades\Auth;

class AnalyticsController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        $user = Auth::user();
        
        if ($user->role === 'trainer') {
            return $this->trainerAnalytics($user);
        } else {
            return $this->traineeAnalytics($user);
        }
    }
    
    private function traineeAnalytics($user)
    {
        // Workout stats
        $totalWorkouts = Workout::where('user_id', $user->id)->count();
        $completedWorkouts = Workout::where('user_id', $user->id)
            ->whereNotNull('completed_at')
            ->count();
        $completionRate = $totalWorkouts > 0 ? round(($completedWorkouts / $totalWorkouts) * 100) : 0;
        
        // Volume stats
        $totalVolume = (float) ExerciseLog::where('user_id', $user->id)->sum('weight');
        $totalReps = (int) ExerciseLog::where('user_id', $user->id)->sum('reps');
        
        // Weekly progress (simplified for MongoDB)
        $thirtyDaysAgo = now()->subDays(30);
        $weeklyWorkouts = Workout::where('user_id', $user->id)
            ->where('completed_at', '>=', $thirtyDaysAgo)
            ->whereNotNull('completed_at')
            ->orderBy('completed_at', 'asc')
            ->get();
        
        // Group by week manually
        $weeklyProgress = [];
        foreach ($weeklyWorkouts as $workout) {
            $weekKey = $workout->completed_at->format('Y-m');
            if (!isset($weeklyProgress[$weekKey])) {
                $weeklyProgress[$weekKey] = 0;
            }
            $weeklyProgress[$weekKey]++;
        }
        
        // Personal Records
        $prs = ExerciseLog::where('user_id', $user->id)
            ->where('is_pr', true)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        return view('analytics.trainee', compact(
            'totalWorkouts', 'completedWorkouts', 'completionRate',
            'totalVolume', 'totalReps', 'weeklyProgress', 'prs'
        ));
    }
    
    private function trainerAnalytics($user)
    {
        $totalClients = Booking::where('trainer_id', $user->id)
            ->where('status', 'confirmed')
            ->distinct('trainee_id')
            ->count('trainee_id');
            
        $totalSessions = Booking::where('trainer_id', $user->id)->count();
        $totalRevenue = (float) Booking::where('trainer_id', $user->id)
            ->where('status', 'confirmed')
            ->sum('amount');
            
        $averageRating = $user->rating ?? 5.0;
        
        $upcomingSessions = Booking::where('trainer_id', $user->id)
            ->where('session_date', '>', now())
            ->count();
        
        return view('analytics.trainer', compact(
            'totalClients', 'totalSessions', 'totalRevenue',
            'averageRating', 'upcomingSessions'
        ));
    }
}