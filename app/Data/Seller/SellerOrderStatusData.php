<?php
namespace App\Data\Seller;

use App\Enum\Order\OrderStatusEnum;
use App\Enum\Seller\SellerOrderStatusEnum;

class SellerOrderStatusData
{
    protected $type;
    function __construct($type=null)
    {
        $this->type = $type;
    }
    public function getStatus()
    {
        return $this->getSt($this->type);
    }
    private function getSt($type)
    {
        $status_value = 1;
        $status = "SEEN";
        switch ($type) {
            case 'ready_to_ship':
                $status_value = SellerOrderStatusEnum::READY_TO_SHIP;
                $status = "READY_TO_SHIP";
                break;
            case 'dispatched':
                $status_value = SellerOrderStatusEnum::DISPATCHED;
                $status = "DISPATCHED";
                break;
            case 'shiped':
                $status_value = SellerOrderStatusEnum::SHIPED;
                $status = "SHIPED";
                break;
            case 'delivered':
                $status_value = SellerOrderStatusEnum::DELIVERED;
                $status = "DELIVERED";
                break;
            case 'delivered_to_hub':
                $status_value = SellerOrderStatusEnum::DELIVERED_TO_HUB;
                $status = "DELIVERED_TO_HUB";
                break;
            case 'cancel':
                $status_value = SellerOrderStatusEnum::CANCELED;
                $status = "CANCELED";
                break;
            case 'reject':
                $status_value = SellerOrderStatusEnum::REJECTED;
                $status = "REJECTED";
                break;
            default:
                # code...
                break;
        }
        return [ 
            'status'=>$status,
            'status_value'=>$status_value,
        ];
    }
}