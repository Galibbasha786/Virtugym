<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BookingConfirmation extends Mailable
{
    use Queueable, SerializesModels;
    
    public $booking;
    public $trainee;
    public $trainer;
    
    public function __construct($booking, $trainee, $trainer)
    {
        $this->booking = $booking;
        $this->trainee = $trainee;
        $this->trainer = $trainer;
    }
    
    public function build()
    {
        return $this->subject('Booking Confirmation - VirtuGym')
                    ->view('emails.booking-confirmation');
    }
}