<?php

namespace App\Models\Admin\Offer;

use App\Models\Admin\Offer\Product\TopOfferProduct;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use function PHPUnit\Framework\returnSelf;

class TopOffer extends Model
{
    use HasFactory;
    protected $fillable=[
        'title',
        'slug',
        'from',
        'to',
        'is_fixed',
        'offer',
        'image',
        'status',
    ];
    public function offerProducts()
    {
        return $this->belongsToMany(Product::class, 'top_offer_products');
    }
}
