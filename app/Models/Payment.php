<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'user_id',
        'batch_id',
        'amount',
        'razorpay_order_id',
        'razorpay_payment_id',
        'razorpay_signature',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }
}
