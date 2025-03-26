<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeliveryCharge extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable=[
        'logistic_id',
        'delivery_route_id',
        'branch_delivery',
        'branch_express_delivery',
        'branch_normal_delivery',
        'door_delivery',
        'door_express_delivery',
        'door_normal_delivery',
        'currency',
    ];

    public function delivery_route()
    {
        return $this->belongsTo(DeliveryRoute::class, 'delivery_route_id');
    }
    public function logistic()
    {
        return $this->belongsTo(Logistics::class, 'logistic_id');
    }
}
