<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RetailerOfferRetailerList extends Model
{
    use HasFactory;
    protected $fillable=[
        'retailer_offer_id',
        'retailer_id'
    ];
}
