<?php

namespace App\Models\Seller;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellerDocument extends Model
{
    use HasFactory;
    protected $fillable=[
        'created_by',
        'seller_id',
        'title',
        'document',
    ];
}
