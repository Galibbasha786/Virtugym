<?php

namespace App\Http\Controllers\Trainee;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Booking;
use App\Models\Workout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TraineeDashboardController extends Controller
{
    public function index()
    {
        $trainee = Auth::user();
        
        $stats = [
            'total_workouts' => Workout::where('user_id', $trainee->id)->count(),
            'completed_workouts' => Workout::where('user_id', $trainee->id)->whereNotNull('completed_at')->count(),
            'total_bookings' => Booking::where('trainee_id', $trainee->id)->count(),
            'upcoming_sessions' => Booking::where('trainee_id', $trainee->id)->where('session_date', '>', now())->count(),
        ];
        
        $recentWorkouts = Workout::where('user_id', $trainee->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
            
        $upcomingSessions = Booking::where('trainee_id', $trainee->id)
            ->where('session_date', '>', now())
            ->with('trainer')
            ->orderBy('session_date', 'asc')
            ->limit(5)
            ->get();
            
        $availableTrainers = User::where('role', 'trainer')
            ->where('is_verified', true)
            ->limit(6)
            ->get();
        
        return view('trainee.dashboard', compact('stats', 'recentWorkouts', 'upcomingSessions', 'availableTrainers'));
    }
    
    public function trainers()
    {
        $trainers = User::where('role', 'trainer')
            ->where('is_verified', true)
            ->paginate(12);
            
        return view('trainee.trainers', compact('trainers'));
    }
    
    public function bookTrainer($id)
    {
        $trainer = User::findOrFail($id);
        return view('trainee.book-trainer', compact('trainer'));
    }
    
    public function myBookings()
    {
        $bookings = Booking::where('trainee_id', Auth::id())
            ->with('trainer')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
            
        return view('trainee.my-bookings', compact('bookings'));
    }
    
    public function myWorkouts()
    {
        $workouts = Workout::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(20);
            
        return view('trainee.my-workouts', compact('workouts'));
    }
}
