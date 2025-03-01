<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'buying_price', 'sell_price', 'supplier_id','link','product_category_type_id','stock'];

    public function supplier()
    {
        return $this->belongsTo(Participant::class, 'supplier_id','id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function product_category_type()
    {
        return $this->belongsTo(ProductCategoryType::class,'product_category_type_id');
    }
    public function image(): MorphOne
    {
        return $this->morphOne(Image::class, 'imageable');
    }
}
