<?php
namespace App\Data\Api;
use App\Http\Controllers\Customer\RefundController;

class ReturnStatusData{

    protected $item;
    public function __construct($item)
    {
        $this->item=$item;
    }
    public function getStatus()
    {
        
        
        switch($this->item->status)
        {
            case 1:
                return 'PENDING';
                break;
            case 2:
                return 'APPROVED';
                break;
            case 3:
                return 'RETURNED';
                break;
            case 4:
                return 'REJECTED';
                break;
            case 5:
                return 'RECIEVED';
                break;
            default:
                return 'PENDING';
                break;
        }
    }

    public function getRefundStatus()
    {
        if((int)$this->item->status === 5 && $this->item->refundData===null)
        {
            $this->item->setAttribute('applyRefund',true);
            return 'Apply Refund';
        }elseif((int)$this->item->status === 5 && $this->item->refundData!=null && $this->item->refundData->status==='1')
        {
            return 'Refund Pending';
        }elseif((int)$this->item->status === 5 && $this->item->refundData!=null && $this->item->refundData->status==='2')
        {
            return 'Refund Paid';
        }elseif((int)$this->item->status === 5 && $this->item->refundData!=null && $this->item->refundData->status==='3')

        {
            return 'Refund Rejected';
        }
        else

        {
            return '';
        }
    }
}