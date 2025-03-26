<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellerCommissionBrand extends Model
{
    use HasFactory;
    protected $fillable=[
        'seller_commissions',
        'brand_id'
    ];
}
