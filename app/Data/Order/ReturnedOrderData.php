<?php
namespace App\Data\Order;

use App\Enum\Order\ReturnedOrderStatusEnum;
use App\Models\Customer\ReturnOrder;
use Illuminate\Support\Arr;

class ReturnedOrderData
{
    protected $filters;
    function __construct($filters=[])
    {
        $this->filters = $filters;
    }
    public function getData()
    {
        $data = ReturnOrder::when(Arr::get($this->filters, 'status'), function($q, $value){
            if($value=="PENDING"){
                $q->where('is_new', true)->where('status', ReturnedOrderStatusEnum::PENDING);
            }
            if($value=="APPROVED"){
                $q->where('status', ReturnedOrderStatusEnum::APPROVED);
            }
            if($value=="RETURNED"){
                $q->where('status', ReturnedOrderStatusEnum::RETURNED);
            }
            if($value=="REJECTED"){
                $q->where('status', ReturnedOrderStatusEnum::REJECTED);
            }
            if($value=="RECEIVED"){
                $q->where('status', ReturnedOrderStatusEnum::RECEIVED);
            }
        })->orderBy('created_at','DESC');
        return $data;
    }
}