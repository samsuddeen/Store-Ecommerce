<?php   
namespace App\Data\Order;

use Illuminate\Support\Arr;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

class OrderData{
    protected $filters;
    protected $data = [];

    function __construct($filters=null)
    {
        $this->filters = $filters;
    }

    public function orderReport()
    {   
        $order = Order::when(Arr::get($this->filters, 'year'), function($q, $value){
            $q->whereYear('created_at', $value);
        })
        ->when(Arr::get($this->filters,'month'),function($q ,$value){
            $q->whereMonth('created_at',$value);
        })
        ->when(Arr::get($this->filters,'district'),function($q,$value){
            $q->where('district',$value);
        })
        ->when(Arr::get($this->filters,'province'),function($q,$value){
            $q->where('province',$value);
        })
        ->when(Arr::get($this->filters,'area'),function($q,$value){
            $q->where('area',$value);
        })
        ->latest()
        ->get();
        
        return $order;
    }



}
