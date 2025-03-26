<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPaymentId extends Model
{
    use HasFactory;

    protected $primarykey='user_id';

    protected $fillable=[
        'user_id',
        'payment_bill_id'
    ];
}
