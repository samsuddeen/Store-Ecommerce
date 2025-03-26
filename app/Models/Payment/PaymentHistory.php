<?php

namespace App\Models\Payment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentHistory extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable=[
        'from_model',
        'from_id',
        'to_model',
        'to_id',
        'reason_model',
        'reason_id',
        'method',
        'method_model',
        'method_id',
        'title',
        'summary',
        'url',
        'is_read',
        'is_received',
    ];
}
