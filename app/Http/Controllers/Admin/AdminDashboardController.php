<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_trainers' => User::where('role', 'trainer')->count(),
            'total_trainees' => User::where('role', 'trainee')->count(),
            'total_bookings' => Booking::count(),
            'total_revenue' => Payment::where('status', 'completed')->sum('amount'),
            'pending_trainers' => User::where('role', 'trainer')->where('is_verified', false)->count(),
        ];
        
        $recentUsers = User::orderBy('created_at', 'desc')->limit(10)->get();
        $recentBookings = Booking::with(['trainee', 'trainer'])->orderBy('created_at', 'desc')->limit(10)->get();
        
        return view('admin.dashboard', compact('stats', 'recentUsers', 'recentBookings'));
    }
    
    public function users()
    {
        $users = User::paginate(20);
        return view('admin.users', compact('users'));
    }
    
    public function trainers()
    {
        $trainers = User::where('role', 'trainer')->paginate(20);
        return view('admin.trainers', compact('trainers'));
    }
    
    public function verifyTrainer($id)
    {
        $trainer = User::findOrFail($id);
        $trainer->is_verified = true;
        $trainer->save();
        
        return redirect()->back()->with('success', 'Trainer verified successfully!');
    }
    
    public function bookings()
    {
        $bookings = Booking::with(['trainee', 'trainer'])->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.bookings', compact('bookings'));
    }
}