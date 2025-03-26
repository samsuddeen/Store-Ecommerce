<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellerOrderCancel extends Model
{
    use HasFactory;
    protected $fillable=[
        'seller_id',
        'reason',
        'product_seller_order_id',
        'product_id',
        'seller_order_id',
        'order_id',
        'cancel_status'
    ];
}
