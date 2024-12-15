<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'buying_price', 'sell_price', 'supplier_id'];

    public function supplier()
    {
        return $this->belongsTo(Participant::class, 'supplier_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function categories()
    {
        return $this->hasMany(ProductCategory::class);
    }

    public function types()
    {
        return $this->hasMany(ProductType::class);
    }
}
