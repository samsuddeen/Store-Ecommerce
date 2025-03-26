<?php

namespace App\Models\Payout;

use App\Models\Seller;
use App\Models\Order\Seller\SellerOrder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payout extends Model
{
    use HasFactory;
    protected $fillable=[
        'created_by',
        'seller_id',
        'seller_order_id',
        'is_new',
        'status',
    ];
    
    public function transaction():BelongsTo
    {
        return $this->belongsTo(SellerOrder::class, 'seller_order_id')->withDefault();
    }
    public function seller():BelongsTo
    {
        return $this->belongsTo(Seller::class,'seller_id');
    }
    
}
