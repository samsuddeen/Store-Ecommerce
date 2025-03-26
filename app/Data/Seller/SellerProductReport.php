<?php

namespace App\Data\Seller;

use App\Models\Product;
use Illuminate\Support\Arr;

class SellerProductReport{

    protected $filters;
    public function __construct($filters)
    {
        $this->filters=$filters;
    }
    public function sellerProductReport($seller)
    {
        $seller_product=Product::where('seller_id',$seller->id)->get();
        
        $data=$seller_product
                ->when(Arr::get($this->filters,'year'),function($item,$value)
                {
                    return $item->filter(function($q) use ($value)
                                {
                                    return date('Y',strtotime($q->created_at))==$value;
                                });
                })
                ->when(Arr::get($this->filters,'month'),function($item,$value){
                    return $item->filter(function($q) use ($value){
                        return date('m',strtotime($q->created_at))==$value;
                    });
                });
        
        return $data;
    }
}