<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class WithdrawalRequest extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'withdrawal_requests';
    
    protected $fillable = [
        'trainer_id', 'amount', 'upi_id', 'status', 
        'remarks', 'approved_at', 'rejected_at'
    ];
    
    protected $casts = [
        'amount' => 'float',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];
    
    public function trainer()
    {
        return $this->belongsTo(User::class, 'trainer_id');
    }
}