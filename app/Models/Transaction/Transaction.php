<?php

namespace App\Models\Transaction;

use App\Models\Order;
use App\Models\Order\Seller\SellerOrder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable=[
        'created_by',
        'order_id',
        'transaction_no',
        'transaction_date',
    ];
    public function order():BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function sellerOrder()
    {
        return $this->hasOne(SellerOrder::class,'order_id','order_id');
    }
   
}
