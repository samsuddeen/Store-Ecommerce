<?php
namespace App\Data\Admin;

use App\Models\Order;
use Illuminate\Support\Arr;

class TransactionReport{

    protected $filters;
    public function __construct($filters)
    {
        $this->filters=$filters;
    }

    public function orderProductReport()
    {
        $order=Order::orderBy('id','Desc')->get();
        $data=$order
                ->when(Arr::get($this->filters,'year'),function($item,$value){
                    return $item->filter(function($q) use ($value){
                       return date('Y',strtotime($q->created_at))==$value;
                    });
                })
                ->when(Arr::get($this->filters,'month'),function($item,$value){
                    return $item->filter(function($q) use ($value){
                        return date('m',strtotime($q->created_at))==$value;
                     });
                })
                ->when(Arr::get($this->filters,'payment'),function($item,$value){
                    return $item->filter(function($q) use ($value){
                        return $q->payment_with==$value;
                     });
                });

        return $data;
    }
}