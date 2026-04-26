<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\User;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Razorpay\Api\Api;

class BookingController extends Controller
{
    private $razorpay;
    
    /**
     * Constructor to initialize Razorpay
     */
    public function __construct()
    {
        // Initialize Razorpay API with keys from .env
        try {
            $this->razorpay = new Api(
                env('RAZORPAY_KEY'),
                env('RAZORPAY_SECRET')
            );
        } catch (\Exception $e) {
            \Log::error('Razorpay initialization failed: ' . $e->getMessage());
        }
    }
    
    /**
     * Show booking form
     * GET /book-trainer/{id}
     */
    public function create($trainer_id)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        if (Auth::user()->role !== 'trainee') {
            abort(403, 'Only trainees can book trainers');
        }
        
        $trainer = User::where('role', 'trainer')->findOrFail($trainer_id);
            
        return view('bookings.create', compact('trainer'));
    }
    
    /**
     * Initiate payment and create booking
     * POST /initiate-payment/{trainer_id}
     */
    public function initiatePayment(Request $request, $trainer_id)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        $validator = Validator::make($request->all(), [
            'session_date' => 'required|date|after:today',
            'session_time' => 'required',
            'duration' => 'required|integer|min:30|max:120',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $trainer = User::findOrFail($trainer_id);
        $amount = ($trainer->hourly_rate ?? 500) * ($request->duration / 60);
        
        try {
            // Create Razorpay Order
            $order = $this->razorpay->order->create([
                'receipt' => 'booking_' . time(),
                'amount' => $amount * 100, // Amount in paise
                'currency' => 'INR',
                'payment_capture' => 1
            ]);
            
            // Store booking data in session
            session([
                'pending_booking' => [
                    'trainer_id' => $trainer_id,
                    'session_date' => $request->session_date,
                    'session_time' => $request->session_time,
                    'duration' => $request->duration,
                    'amount' => $amount,
                    'order_id' => $order->id
                ]
            ]);
            
            return view('payments.razorpay', [
                'order' => $order,
                'amount' => $amount,
                'trainer' => $trainer,
                'razorpay_key' => env('RAZORPAY_KEY')
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Razorpay order creation failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Payment initialization failed: ' . $e->getMessage());
        }
    }
    
    /**
     * Handle payment success
     * POST /payment-success
     */
    public function paymentSuccess(Request $request)
    {
        $pendingBooking = session('pending_booking');
        
        if (!$pendingBooking) {
            return redirect()->route('trainee.trainers')
                ->with('error', 'Booking session expired.');
        }
        
        $attributes = [
            'razorpay_order_id' => $request->razorpay_order_id,
            'razorpay_payment_id' => $request->razorpay_payment_id,
            'razorpay_signature' => $request->razorpay_signature
        ];
        
        try {
            // Verify payment signature
            $this->razorpay->utility->verifyPaymentSignature($attributes);
            
            // Create booking
            $booking = Booking::create([
                'trainee_id' => Auth::id(),
                'trainer_id' => $pendingBooking['trainer_id'],
                'session_date' => $pendingBooking['session_date'] . ' ' . $pendingBooking['session_time'],
                'duration_minutes' => $pendingBooking['duration'],
                'amount' => $pendingBooking['amount'],
                'status' => 'confirmed',
                'payment_id' => $request->razorpay_payment_id
            ]);
            
            // Create payment record
            Payment::create([
                'user_id' => Auth::id(),
                'booking_id' => $booking->id,
                'amount' => $pendingBooking['amount'],
                'currency' => 'INR',
                'status' => 'completed',
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_order_id' => $request->razorpay_order_id,
                'razorpay_signature' => $request->razorpay_signature,
                'paid_at' => now()
            ]);
            
            session()->forget('pending_booking');
            
            return redirect()->route('bookings.index')
                ->with('success', 'Payment successful! Booking confirmed.');
                
        } catch (\Exception $e) {
            \Log::error('Payment verification failed: ' . $e->getMessage());
            return redirect()->route('trainee.trainers')
                ->with('error', 'Payment verification failed. Please try again.');
        }
    }
    
    /**
     * Handle payment failure
     * GET /payment-failed
     */
    public function paymentFailed()
    {
        session()->forget('pending_booking');
        return redirect()->route('trainee.trainers')
            ->with('error', 'Payment failed. Please try again.');
    }
    
    /**
     * Display all bookings
     * GET /bookings
     */
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        if (Auth::user()->role === 'trainer') {
            $bookings = Booking::where('trainer_id', Auth::id())
                ->with('trainee')
                ->orderBy('session_date', 'asc')
                ->paginate(20);
        } else {
            $bookings = Booking::where('trainee_id', Auth::id())
                ->with('trainer')
                ->orderBy('created_at', 'desc')
                ->paginate(20);
        }
        
        return view('bookings.index', compact('bookings'));
    }
    
    /**
     * Update booking status
     * PUT /bookings/{id}
     */
    public function update(Request $request, $id)
    {
        if (!Auth::check() || Auth::user()->role !== 'trainer') {
            abort(403);
        }
        
        $booking = Booking::where('trainer_id', Auth::id())->findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:confirmed,completed,cancelled'
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        
        $booking->update(['status' => $request->status]);
        
        if ($request->status == 'completed') {
            $booking->update(['completed_at' => now()]);
        } elseif ($request->status == 'cancelled') {
            $booking->update(['cancelled_at' => now()]);
        }
        
        return redirect()->back()->with('success', 'Booking status updated!');
    }
    private function sendBookingConfirmation($booking)
{
    $trainee = User::find($booking->trainee_id);
    $trainer = User::find($booking->trainer_id);
    
    try {
        // Send email to trainee
        Mail::to($trainee->email)->send(new \App\Mail\BookingConfirmation($booking, $trainee, $trainer));
        
        // Send email to trainer
        Mail::to($trainer->email)->send(new \App\Mail\BookingConfirmation($booking, $trainee, $trainer));
        
        \Log::info('Booking confirmation emails sent to: ' . $trainee->email . ' and ' . $trainer->email);
        
    } catch (\Exception $e) {
        \Log::error('Email sending failed: ' . $e->getMessage());
    }
}
}
