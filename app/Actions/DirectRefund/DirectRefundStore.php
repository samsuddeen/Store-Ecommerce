<?php
namespace App\Actions\DirectRefund;
use Carbon\Carbon;
use App\Events\PaymentEvent;
use App\Models\DirectRefund;
use Illuminate\Http\Request;
use App\Models\Refund\Refund;
use App\Models\Refund\RefundStatus;
use App\Enum\Order\RefundStatusEnum;
use App\Models\Payment\PaymentMethod;
use App\Data\Payment\PaymentMethodData;
use App\Data\Form\PaymentHistoryFormData;

class DirectRefundStore{
    protected $refund;
    protected $status;
    protected $request;
    function __construct(Request $request, DirectRefund $refund, $status)
    {
        $this->refund = $refund;
        $this->status = $status;
        $this->request = $request;
    }

    public function updateDirectRefundStatus()
    {
        switch ($this->status) {
            case 'PAID':
                $this->refund->update([
                    'status' => RefundStatusEnum::PAID,
                    'is_new'=>false,
                    'paid_by'=>($this->request->payment_method==1) ? 'ESEWA' : (($this->request->payment_method==2) ? 'KHALTI' : 'COD')
                ]);
              
                break;
            case 'REJECTED':
                $this->refund->update([
                    'status' => RefundStatusEnum::REJECTED,
                    'is_new'=>false,
                    'remarks'=>$this->request->remarks ?? ''
                ]);
                
                break;
        }
    }
}