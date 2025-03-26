<?php

namespace App\Models;

use App\Enum\Order\OrderStatusEnum;
use App\Models\Order\OrderStatus;
use App\Models\Order\Seller\SellerOrder;
use App\Models\Task\Task;
use App\Models\Transaction\Transaction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;
    public $primarykey = ['user_id'];

    protected $fillable = [
        'user_id',
        'shipping_charge',
        'total_quantity',
        'total_price',
        'ref_id',
        'coupon_discount_price',
        'coupon_code',
        'coupon_name',
        'admin_approve',
        'seller_approve',
        'total_discount',
        'coupon_name',
        'pending',
        'status',
        'payment_status',
        'deleted_by_customer',
        'payment_date',
        'payment_with',
        // shipping address
        'cancelled',
        'name',
        'phone',
        'email',
        'province',
        'district',
        'area',
        'additional_address',
        'zip',
        'payment_proof',

        // shipping address
        'b_name',
        'b_phone',
        'b_email',
        'b_province',
        'b_district',
        'b_area',
        'b_additional_address',
        'b_zip',
        'is_new',
        'vat_amount',
        'material_charge',
        'guest_ref_id'
    ];

    public function productImage()
    {
        return $this->hasManyThrough(ProductImage::class, Product::class, 'product_id');
    }

    public function product()
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }
    public function user()
    {
        return $this->belongsTo(New_Customer::class, 'user_id','id')->withDefault();
    }
    public function orderAssets()
    {
        return $this->hasMany(OrderAsset::class, 'order_id');
    }
    public function orderStock()
    {
        return $this->hasMany(OrderStock::class, 'order_id');
    }
    public function transaction(): HasOne
    {
        return $this->hasOne(Transaction::class, 'order_id')->withDefault();
    }
    public function sellerOrder()
    {
        return $this->hasMany(SellerOrder::class, 'order_id');
    }

    public function orderStatus()
    {
        return $this->hasOne(OrderStatus::class,'order_id','id');
    }

    public function getprovince()
    {
        return $this->hasOne(Province::class,'id','province');
    }

    public function getDistrict()
    {
        return $this->hasOne(District::class,'id','district');
    }
    public function getCity()
    {
        return $this->hasOne(City::class,'id','area');
    }
    public function directrefund()
    {
        return $this->hasOne(DirectRefund::class,'order_id','id');
    }

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
