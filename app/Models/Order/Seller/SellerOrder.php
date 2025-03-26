<?php

namespace App\Models\Order\Seller;

use App\Enum\Seller\SellerOrderStatusEnum;
use App\Models\Order;
use App\Models\New_Customer;
use App\Models\Payout\Payout;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class SellerOrder extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'order_id',
        'seller_id',
        'user_id',
        'qty',
        'subtotal',
        'discount_percent',
        'total',
        'status',
        'total_discount',
        'is_new',
        'status',
        'payment_status',
        'total_vat_amount'
    ];

    

    public function user()
    {
        return $this->belongsTo(New_Customer::class, 'user_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id')->withDefault();
    }

    public function scopeVisible($query)
    {
        $query->where('seller_id', auth()->guard('seller')->id());
    }
    public function sellerProductOrder()
    {
        return $this->hasMany(ProductSellerOrder::class, 'seller_order_id',);
    }
   
    public function scopeTransaction($query)
    {
        $query->where('status', SellerOrderStatusEnum::DELIVERED);
    }

    public function payout():HasOne
    {
        return $this->hasOne(Payout::class, 'seller_order_id')->withDefault();
    }
}
