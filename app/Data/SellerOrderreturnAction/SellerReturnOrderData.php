<?php
namespace App\Data\SellerOrderreturnAction;
use App\Models\Seller;
use App\Models\Product;
use App\Models\Customer\ReturnOrder;

class SellerReturnOrderData{

    protected $seller;
    public function __construct(Seller $seller)
    {
        $this->seller=$seller;
    }

    public function getData()
    {
        $seller=$this->seller;

        $sellerProduct=ReturnOrder::orderBy('created_at','DESC')->get();
        $sellerProduct=collect($sellerProduct)->map(function($item) use ($seller)
        {
           $product=Product::where('id',$item->product_id)->first();
           if($product->seller_id==$seller->id)
           {
                return $item;
           }

        });
        
        return $sellerProduct;
    }
}