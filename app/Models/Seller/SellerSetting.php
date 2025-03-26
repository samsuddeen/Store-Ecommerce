<?php

namespace App\Models\Seller;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellerSetting extends Model
{
    use HasFactory;
    protected $fillable = [
        'seller_id',
        'banner_image',
        'logo',        
    ];
}
