<?php

namespace App\Models\Delivery;

use App\Models\New_Customer;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryFeedback extends Model
{
    use HasFactory;

    protected $table = "delivery_feedback";

    protected $fillable = [
        'order_id',
        'customer_id',
        'delivery_person_id',
        'rating',
        'message',
        'image'
    ];

    public function customer()
    {
        return $this->belongsTo(New_Customer::class,'customer_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
