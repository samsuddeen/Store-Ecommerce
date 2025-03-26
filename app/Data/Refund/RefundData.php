<?php
namespace App\Data\Refund;

use App\Models\DirectRefund;
use App\Models\Refund\Refund;
use Illuminate\Support\Arr;

class RefundData
{
    protected $filters;
    function __construct($filters)
    {
        $this->filters = $filters;
    }
    public function getData()
    {   
        $data = Refund::when(Arr::get($this->filters, 'type'), function($q, $value){
            $q->where('status', $value);
        })->latest();
        return $data;
    }

    public function getRefundpaidData()
    {
        // $data = Refund::when(Arr::get($this->filters, 'type'), function($q, $value){
        //     $q->where('status', 2);
        // })->orderByDesc('id')->get();
        $data = Refund::where('status','2')->orderByDesc('id')->get();
        return $data;
    }
}