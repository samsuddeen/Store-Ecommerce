<?php

namespace App\Models\Customer;

use App\Models\New_Customer;
use App\Models\OrderAsset;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Refund\Refund;
class ReturnOrder extends Model
{
    use HasFactory;
    protected $fillable = [
        'is_new',
        'product_id',
        'order_asset_id',
        'amount',
        'reason',
        'comment',
        'user_id',
        'status',
        'qty'
    ];

   
    public function product()
    {
        return $this->belongsTo(Product::class, 'id', 'product_id');
    } 

    public function orderAsset(): BelongsTo
    {
        return $this->belongsTo(OrderAsset::class, 'order_asset_id')->withDefault();
    }
    public function owner():BelongsTo
    {
        return $this->belongsTo(New_Customer::class, 'user_id', 'id')->withDefault();
    }

    public function getproduct()
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    } 

    public function refundData()
    {
        return $this->hasOne(Refund::class,'return_id','id');
    }

    public function getOrderAsset()
    {
        return $this->hasOne(OrderAsset::class,'id','order_asset_id');
    }
}
