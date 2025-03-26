<?php

namespace App\Models\Order\Seller;

use App\Models\Product;
use App\Models\SellerOrderCancel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductSellerOrder extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'seller_order_id',
        'product_id',
        'qty',
        'price',
        'discount',
        'image',
        'sub_total',
        'order_id',
        'discount_percent',
        'cancel_status',
        'product_vat_amount'
    ];
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id')->withDefault();
    }

    public function getProduct()
    {
        return $this->hasOne(Product::class,'id','product_id');
    }

    public function cancelStatus()
    {
       return $this->hasOne(SellerOrderCancel::class,'product_seller_order_id','id');
    }
}
