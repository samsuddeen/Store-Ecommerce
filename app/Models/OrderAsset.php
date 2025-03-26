<?php

namespace App\Models;

use App\Models\Color;
use App\Models\Product;
use App\Models\Customer\ReturnOrder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class OrderAsset extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',
        'price',
        'qty',
        'sub_total_price',
        'color',
        'image',
        'discount',
        'options',
        'cancel_status',
        'vatamountfield'
    ];
    protected $casts = [
        'options' => 'array',
    ];

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class, 'product_id');
    }
    public function product()
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }
    public function returnedOrder(): HasOne
    {
        return $this->hasOne(ReturnOrder::class, 'order_asset_id');
    }

    public function getReturnOrder()
    {
        return $this->hasOne(ReturnOrder::class, 'order_asset_id', 'id');
    }

    public function getColor()
    {
        return $this->hasOne(Color::class, 'id', 'color');
    }
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function getImage()
    {
        return $this->hasMany(ProductImage::class,'product_id','product_id');
    }

    public function cancelStatus()
    {
        return $this->belongsTo(SellerOrderCancel::class, 'order_id', 'order_id')->where('product_id', $this->product_id);
    }
    

    
}
