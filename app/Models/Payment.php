<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = ['order_id', 'payment_date', 'amount','payment_method_id'];

    protected $casts = [
        'payment_date' => 'datetime', // Cast payment_date to a Carbon instance
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function payment_method()
    {
        return $this->belongsTo(PaymentMethod::class,'payment_method_id');
    }
}
