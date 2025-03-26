<?php
namespace App\Data\Payment;

use App\Models\Payment\PaymentHistory;
use Illuminate\Support\Arr;

class PaymentHistoryData
{
    protected $filters;
    protected $data;
    function __construct($filters=[])
    {
        $this->filters = $filters;
    }
    public function getData()
    {
        $this->setData();
        return $this->data;
    }
    private function setData()
    {
        $this->data =  PaymentHistory::when(Arr::get($this->filters, 'year'), function($q, $value){
            $q->whereYear('created_at', $value);
        })->when(Arr::get($this->filters, 'month'), function($q, $value){
            $q->whereMonth('created_at', $value);
        })->when(Arr::get($this->filters, 'status'), function($q, $value){
            $q->where('is_received', $value);
        })->orderByDesc('created_at')->get();
    }
}