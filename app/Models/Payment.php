<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = ['order_id', 'payment_date', 'amount', 'amount_shipping', 'amount_overhead'];

    protected $casts = [
        'payment_date' => 'datetime', // Cast payment_date to a Carbon instance
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
