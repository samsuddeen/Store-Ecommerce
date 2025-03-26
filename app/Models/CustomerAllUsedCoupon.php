<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerAllUsedCoupon extends Model
{
    use HasFactory;
    protected $fillable=[
        'coupon_id',
        'customer_coupon_id',
        'customer_id',
        'coupon_code'
    ];
}
