<?php
namespace App\Data\Seller;

use Illuminate\Support\Arr;
use App\Models\Admin\SearchKeyword;
use App\Models\Order\Seller\SellerOrder;

class UsersearchData{

    protected $filters;
    public function __construct($filters)
    {
        $this->filters=$filters;
    }
    public function sellerUserReport($seller)
    {
        $seller_order=SellerOrder::where('seller_id',$seller->id)->where('status',5)->get();
        $user_id=collect($seller_order)->unique('user_id');
        $user_id=$user_id->map(function($item){
            return $item->user_id;
        });
        
        
        $keyword=SearchKeyword::whereIn('customer_id',$user_id)->get();
        $data=$keyword
                ->when(Arr::get($this->filters,'year'),function($item,$value)
                {
                    return $item->filter(function($q) use ($value){
                        return date('Y',strtotime($q->created_at))==$value;
                    });
                })
                ->when(Arr::get($this->filters,'month'),function($item,$value)
                {
                    return $item->filter(function($q) use ($value){
                        return date('m',strtotime($q->created_at))==$value;
                    });
                });

        return $data;
    }
}