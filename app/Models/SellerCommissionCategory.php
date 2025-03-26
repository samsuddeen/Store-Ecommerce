<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellerCommissionCategory extends Model
{
    use HasFactory;
    protected $fillable=[
        'seller_commisssion_id',
        'category_id'
    ];
}
