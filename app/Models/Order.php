<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['participant_id', 'order_type', 'order_date', 'order_status_id','shipping_cost'];

    protected $casts = [
        'order_date' => 'datetime', // Cast order_date to a Carbon instance
    ];

    public function order_transactions()
    {
        return $this->hasMany(OrderTransaction::class);
    }

    public function participant()
    {
        return $this->belongsTo(Participant::class);
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
    public function getTotalOrderAmount()
    {
        $totalOrderAmount = $this->order_transactions()->sum(DB::raw('quantity * (SELECT sell_price FROM products WHERE products.id = order_transactions.product_id)')) + $this->shipping_cost;
        return $totalOrderAmount;
    }
    public function getPaymentProgress()
    {
        $totalPaid = $this->payments()->sum('amount');

        // Calculate the total order amount
        // $totalOrderAmount = $this->order_transactions()->sum(DB::raw('quantity * (SELECT sell_price FROM products WHERE products.id = order_transactions.product_id)'));
        // involve shipping cost
        $totalOrderAmount = $this->order_transactions()->sum(DB::raw('quantity * (SELECT sell_price FROM products WHERE products.id = order_transactions.product_id)')) + $this->shipping_cost;

        // Calculate the payment percentage
        if ($totalOrderAmount > 0) {
            $paymentPercentage = ($totalPaid / $totalOrderAmount) * 100;
        } else {
            $paymentPercentage = 0; // Avoid division by zero
        }

        return floor($paymentPercentage);
    }
}
