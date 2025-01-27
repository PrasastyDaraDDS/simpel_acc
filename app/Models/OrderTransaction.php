<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderTransaction extends Model
{
    use HasFactory;

    protected $fillable = ['order_id', 'product_id', 'quantity'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getRawPrice($order_type){
        if($order_type == 'buy'){
            return $this->product->buying_price;
        }
        return$this->product->sell_price;
    }
    public function getProductTotalPrice($order_type){
        return $this->quantity * $this->getRawPrice($order_type);
    }
}
