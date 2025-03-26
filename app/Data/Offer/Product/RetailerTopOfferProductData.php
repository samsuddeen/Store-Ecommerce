<?php
namespace App\Data\Offer\Product;

use App\Models\Product;
use App\Models\Admin\Offer\TopOffer;
use App\Models\RetailerOfferSection;

class RetailerTopOfferProductData
{
    function __construct()
    {
        
    }
    public function getData()
    {
        $products = Product::orderBy('name')->get();
        $data = [];
        $data['products'] = $products;
        return $data;
    }
}