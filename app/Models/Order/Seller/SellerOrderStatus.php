<?php

namespace App\Models\Order\Seller;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SellerOrderStatus extends Model
{
    use HasFactory;
    protected $fillable=[
        'updated_by',
        'seller_order_id',
        'status',
        'date',
        'remarks',
    ];
    public function order():BelongsTo
    {
        return $this->belongsTo(SellerOrder::class, 'seller_order_id')->withDefault();
    }
}
