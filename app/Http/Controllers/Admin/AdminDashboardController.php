<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\WithdrawalRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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
            'pending_withdrawals' => WithdrawalRequest::where('status', 'pending')->count(),
            'total_withdrawn' => WithdrawalRequest::where('status', 'completed')->get()->sum('amount'),
        ];
        
        $recentUsers = User::orderBy('created_at', 'desc')->limit(10)->get();
        $recentBookings = Booking::with(['trainee', 'trainer'])->orderBy('created_at', 'desc')->limit(10)->get();
        
        return view('admin.dashboard', compact('stats', 'recentUsers', 'recentBookings'));
    }
    
    public function users()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(20);
        return view('admin.users', compact('users'));
    }
    
    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        
        // Prevent deleting admin
        if ($user->role === 'admin') {
            return redirect()->back()->with('error', 'Cannot delete admin user.');
        }
        
        $user->delete();
        return redirect()->back()->with('success', 'User deleted successfully.');
    }
    
    public function blockUser($id)
    {
        $user = User::findOrFail($id);
        $user->is_blocked = !($user->is_blocked ?? false);
        $user->save();
        
        $status = $user->is_blocked ? 'blocked' : 'unblocked';
        return redirect()->back()->with('success', "User {$status} successfully.");
    }
    
    public function trainers()
    {
        $trainers = User::where('role', 'trainer')->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.trainers', compact('trainers'));
    }
    
    public function verifyTrainer($id)
    {
        $trainer = User::findOrFail($id);
        $trainer->is_verified = true;
        $trainer->save();
        
        return redirect()->back()->with('success', 'Trainer verified successfully!');
    }
    
    public function unverifyTrainer($id)
    {
        $trainer = User::findOrFail($id);
        $trainer->is_verified = false;
        $trainer->save();
        
        return redirect()->back()->with('success', 'Trainer unverified.');
    }
    
    public function deleteTrainer($id)
    {
        $trainer = User::findOrFail($id);
        $trainer->delete();
        
        return redirect()->back()->with('success', 'Trainer deleted successfully.');
    }
    
    public function bookings()
    {
        $bookings = Booking::with(['trainee', 'trainer'])->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.bookings', compact('bookings'));
    }
    
    public function deleteBooking($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->delete();
        
        return redirect()->back()->with('success', 'Booking deleted successfully.');
    }
    
    // Withdrawal Requests
    public function withdrawals()
    {
        $requests = WithdrawalRequest::with('trainer')->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.withdrawals', compact('requests'));
    }
    
    public function approveWithdrawal($id)
    {
        $request = WithdrawalRequest::findOrFail($id);
        $request->status = 'completed';
        $request->approved_at = now();
        $request->save();
        
        return redirect()->back()->with('success', "Withdrawal of ₹{$request->amount} approved. Amount marked as transferred.");
    }
    
    public function rejectWithdrawal($id)
    {
        $request = WithdrawalRequest::findOrFail($id);
        $request->status = 'rejected';
        $request->save();
        
        return redirect()->back()->with('success', 'Withdrawal request rejected.');
    }
}