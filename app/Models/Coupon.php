<?php

namespace App\Models;

use App\Models\Admin\Coupon\Customer\CustomerCoupon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coupon extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable=[
        'user_id',
        'title',
        'coupon_code',
        'summary',
        'description',
        'discount',
        'is_percentage',
        'publishStatus',
        'slug',
        'from',
        'to',
        'currency_id',
    ];
    public function customerCoupon()
    {
        return $this->hasMany(CustomerCoupon::class, 'coupon_id');
    }
}
