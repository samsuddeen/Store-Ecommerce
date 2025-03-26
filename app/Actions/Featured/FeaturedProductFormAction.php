<?php
namespace App\Actions\Featured;

use App\Models\Admin\Product\Featured\FeaturedSection;
use App\Models\Product;

class FeaturedProductFormAction
{
   
    function __construct()
    {
        
    }
    public function getData()
    {
        $data = [
            'products'=>$this->getAllProducts(),
            'featured_sections'=>$this->getAllFeaturedSection(),
        ];
        return $data;
    }
    private function getSingleFeaturedSection()
    {
       
    }
    
    private function getAllProducts()
    {
        return Product::latest()->get();
    }

    public function getAllFeaturedSection()
    {
        return FeaturedSection::latest()->get();
    }

    
}