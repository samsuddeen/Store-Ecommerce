<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DirectRefund extends Model
{
    use HasFactory;
    protected $fillable=[
        'user_id',
        'is_new',
        'refund_details',
        'status',
        'paid_from',
        'paid_by',
        'order_id',
        'remarks'
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
