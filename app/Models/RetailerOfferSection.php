<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RetailerOfferSection extends Model
{
    use HasFactory;
    protected $fillable=[
        'title',
        'slug',
        'is_fixed',
        'offer',
        'image',
        'status',
    ];
    public function productList()
    {
        return $this->hasMany(RetailerOfferProductList::class, 'retailer_offer_id','id');
    }

    public function retailerList()
    {
        return $this->hasMany(RetailerOfferRetailerList::class, 'retailer_offer_id','id');
    }
}
