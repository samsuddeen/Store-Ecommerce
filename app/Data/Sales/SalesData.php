<?php
namespace App\Data\Sales;

use Illuminate\Support\Arr;
use App\Models\New_Customer;
use Illuminate\Support\Facades\DB;
use App\Enum\Order\OrderStatusEnum;
use App\Enum\Customer\CustomerStatusEnum;
use App\Enum\Social\SocialEnum;
use App\Models\Transaction\Transaction;

class SalesData
{
    protected $filters;
    public function __construct($filters)
    {   
            $this->filters = $filters;
    }


    public function salesData()
    {
        $sales = Transaction::when(Arr::get($this->filters ,'month'),function($q,$value){
            $q->whereMonth('created_at',$value);
        })
        ->when(Arr::get($this->filters,'year'),function($q , $value){
            $q->whereYear('created_at', $value);
        })
        ->orderBy('created_at','DESC')->get();
      return $sales;
    }
}