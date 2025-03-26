<?php

namespace App\Models\Admin\Coupon\Customer;

use App\Models\Coupon;
use App\Models\New_Customer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerCoupon extends Model
{
    use HasFactory;
    protected $fillable=[
        'coupon_id',
        'customer_id',
        'is_expired',
        'is_for_same',
        'code',
        'status',
    ];
    public function coupon()
    {
        return $this->belongsTo(Coupon::class, 'coupon_id')->withDefault();
    }
    public function customer()
    {
        return $this->belongsTo(New_Customer::class, 'customer_id')->withDefault();
    }
   
}
