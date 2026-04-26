<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendBookingReminders extends Command
{
    protected $signature = 'bookings:send-reminders';
    protected $description = 'Send booking reminders to trainees and trainers';
    
    public function handle()
    {
        $tomorrow = now()->addDay()->startOfDay();
        $bookings = Booking::whereDate('session_date', $tomorrow)
            ->where('status', 'confirmed')
            ->get();
        
        foreach ($bookings as $booking) {
            $trainee = User::find($booking->trainee_id);
            $trainer = User::find($booking->trainer_id);
            
            if ($trainee && $trainer) {
                // Send reminder email to trainee
                Mail::raw("Reminder: You have a booking tomorrow at " . $booking->session_date, function($message) use ($trainee) {
                    $message->to($trainee->email)
                            ->subject('Booking Reminder - VirtuGym');
                });
                
                // Send reminder email to trainer
                Mail::raw("Reminder: You have a session tomorrow with " . $trainee->name, function($message) use ($trainer) {
                    $message->to($trainer->email)
                            ->subject('Booking Reminder - VirtuGym');
                });
            }
        }
        
        $this->info('Reminders sent for ' . $bookings->count() . ' bookings');
        return Command::SUCCESS;
    }
}