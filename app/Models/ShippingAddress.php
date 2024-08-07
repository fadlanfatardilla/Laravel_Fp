<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'receiver_name',
        'receiver_phone',
        'address',
        'city',
        'province',
        'street_name',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}