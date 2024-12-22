<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['participant_id', 'product_id', 'quantity', 'order_type', 'order_date', 'order_status_id'];

    protected $casts = [
        'order_date' => 'datetime', // Cast order_date to a Carbon instance
    ];

    public function participant()
    {
        return $this->belongsTo(Participant::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function status()
    {
        return $this->belongsTo(OrderStatus::class, 'order_status_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function resellerOrder()
    {
        return $this->hasOne(ResellerOrder::class);
    }
    public function getPaymentProgress()
    {
        // Calculate the total sell price
        $totalSellPrice = $this->quantity * $this->product->sell_price;

        // Calculate the total amount paid
        $totalPayments = $this->payments->sum('amount');

        // Calculate the payment progress
        if ($totalSellPrice == 0) {
            return 0;
        }

        $paymentProgress = ($totalPayments / $totalSellPrice) * 100;

        return floor($paymentProgress);
    }
}
