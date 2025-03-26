<?php
namespace App\Data\Seller;

use Illuminate\Support\Arr;
use App\Models\New_Customer;
use App\Models\Order\Seller\SellerOrder;

class CustomerReportData{

    protected $filters;
    public function __construct($filters)
    {
        $this->filters=$filters;
    }
    public function CustomerReportData($seller)
    {
        $seller_order=SellerOrder::where('seller_id',$seller->id)->get();
        $user_order=collect($seller_order)->unique('user_id');
        $user=collect($user_order)->pluck('user_id');

        $user_data=New_Customer::whereIn('id',$user)->get();
        
        $data=$user_data
                ->when(Arr::get($this->filters,'area'),function($item,$value)
                {
                   return $item->where('area',$value);
                })
                ->when(Arr::get($this->filters,'year'),function($item,$value){
                    return $item->filter(function($q) use ($value){
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