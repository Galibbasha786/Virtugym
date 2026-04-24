<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Payment extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'payments';
    
    protected $fillable = [
        'user_id', 'booking_id', 'amount', 'currency', 'status',
        'razorpay_payment_id', 'razorpay_order_id', 'razorpay_signature',
        'payment_method', 'paid_at'
    ];
    
    protected $casts = [
        'amount' => 'float',
        'paid_at' => 'datetime',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}