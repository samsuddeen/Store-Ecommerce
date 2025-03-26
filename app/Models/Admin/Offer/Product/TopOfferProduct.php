<?php

namespace App\Models\Admin\Offer\Product;

use App\Models\Admin\Offer\TopOffer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TopOfferProduct extends Model
{
    use HasFactory;
    protected $fillable=[
        'product_id',
        'top_offer_id',
    ];

    public function getOffer()
    {
        return $this->hasOne(TopOffer::class,'id','top_offer_id');
    }
}
