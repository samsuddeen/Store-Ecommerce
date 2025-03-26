<?php
namespace App\Data\Order;

use App\Enum\Order\OrderStatusEnum;

class OrderStatusData
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
                $status_value = OrderStatusEnum::READY_TO_SHIP;
                $status = "READY_TO_SHIP";
                break;
            case 'dispatched':
                $status_value = OrderStatusEnum::DISPATCHED;
                $status = "DISPATCHED";
                break;
            case 'shiped':
                $status_value = OrderStatusEnum::SHPIED;
                $status = "SHPIED";
                break;
            case 'delivered':
                $status_value = OrderStatusEnum::DELIVERED;
                $status = "DELIVERED";
                break;
            case 'cancel':
                $status_value = OrderStatusEnum::CANCELED;
                $status = "CANCELED";
                break;
            case 'reject':
                $status_value = OrderStatusEnum::REJECTED;
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