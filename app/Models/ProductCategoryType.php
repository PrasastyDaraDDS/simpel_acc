<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategoryType extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'product_category_id'];

    public function product_category()
    {
        return $this->belongsTo(ProductCategory::class,'product_category_id');
    }
}
