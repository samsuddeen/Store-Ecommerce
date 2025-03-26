<?php
namespace App\Traits\Product\Featured;

use App\Models\Admin\Product\Featured\FeaturedSection;
use App\Models\Admin\Product\Featured\FeaturedSectionProduct;
use Illuminate\Http\Request;

trait FeaturedProductSection
{
    public function check(Request $request)
    {
        $data = FeaturedSectionProduct::where(['featured_section_id'=>$request->featured_section_id, 'product_id'=>$request->product_id])->first();
        if(empty($data)){
            return false;
        }else{
            return true;
        }
    }
    public function getData(FeaturedSection $featuredSection)
    {
        $data = [
            'featured_products'=>$featuredSection->featuredProducts(),
        ];
        return $data;
    }
}
