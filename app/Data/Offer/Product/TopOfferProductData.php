<?php
namespace App\Data\Offer\Product;

use App\Models\Admin\Offer\TopOffer;
use App\Models\Product;

class TopOfferProductData
{
    function __construct()
    {
        
    }
    public function getData()
    {
        $topOffers = TopOffer::orderBy('title')->get();
        $products = Product::orderBy('name')->get();
        $data = [];
        $data['offers'] = $topOffers;
        $data['products'] = $products;
        return $data;
    }
}