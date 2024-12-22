<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'address','link','participant_role_id'];

    public function products()
    {
        return $this->hasMany(Product::class, 'supplier_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function role()
    {
        return $this->belongsTo(ParticipantRole::class,'participant_role_id');
    }
}
