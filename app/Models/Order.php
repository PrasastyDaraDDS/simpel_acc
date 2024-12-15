<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['participant_id', 'product_id', 'quantity', 'order_date', 'order_status_id'];

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
}
