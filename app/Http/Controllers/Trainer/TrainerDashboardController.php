<?php
namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use App\Models\Workout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrainerDashboardController extends Controller
{
    public function index()
    {
        $trainer = Auth::user();
        
        $stats = [
            'total_clients' => Booking::where('trainer_id', $trainer->id)->where('status', 'active')->count(),
            'total_sessions' => Booking::where('trainer_id', $trainer->id)->count(),
            'completed_sessions' => Booking::where('trainer_id', $trainer->id)->where('status', 'completed')->count(),
            'total_earned' => Booking::where('trainer_id', $trainer->id)->where('status', 'completed')->sum('amount'),
            'upcoming_sessions' => Booking::where('trainer_id', $trainer->id)->where('status', 'active')->where('session_date', '>', now())->count(),
            'rating' => $trainer->rating ?? 0,
        ];
        
        $upcomingBookings = Booking::where('trainer_id', $trainer->id)
            ->where('session_date', '>', now())
            ->with('trainee')
            ->orderBy('session_date', 'asc')
            ->limit(5)
            ->get();
            
        $myWorkouts = Workout::where('user_id', $trainer->id)->orderBy('created_at', 'desc')->limit(5)->get();
        
        return view('trainer.dashboard', compact('stats', 'upcomingBookings', 'myWorkouts'));
    }
    
    public function clients()
    {
        $clients = Booking::where('trainer_id', Auth::id())
            ->where('status', 'active')
            ->with('trainee')
            ->get();
            
        return view('trainer.clients', compact('clients'));
    }
    
    public function schedule()
    {
        $bookings = Booking::where('trainer_id', Auth::id())
            ->where('session_date', '>', now())
            ->with('trainee')
            ->orderBy('session_date', 'asc')
            ->paginate(20);
            
        return view('trainer.schedule', compact('bookings'));
    }
    
    public function updateProfile(Request $request)
    {
        $trainer = Auth::user();
        
        $request->validate([
            'bio' => 'nullable|string',
            'experience_years' => 'nullable|integer',
            'specialization' => 'nullable|string',
            'hourly_rate' => 'nullable|numeric',
            'certifications' => 'nullable|string',
        ]);
        
        $trainer->update($request->only(['bio', 'experience_years', 'specialization', 'hourly_rate', 'certifications']));
        
        return redirect()->back()->with('success', 'Profile updated successfully!');
    }
}