<?php

namespace App\Models;

use App\Models\Order;
use App\Models\New_Customer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductCancelReason extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_id',
        'reason',        
        'additional_reason',
        'user_id',
    ];

    public function user()
    {
        return $this->hasOne(New_Customer::class,'id','user_id');
    }

    public function order()
    {
        return $this->hasOne(Order::class,'id','order_id');
    }
}
