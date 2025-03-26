<?php

namespace App\Models\Order;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderStatus extends Model
{
    use HasFactory;
    protected $fillable=[
        'updated_by',
        'order_id',
        'status',
        'date',
        'remarks',
    ];
    public function order():BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id')->withDefault();
    }
}
