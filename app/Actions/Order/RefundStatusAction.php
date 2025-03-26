<?php
namespace App\Actions\Order;

use App\Data\Form\PaymentHistoryFormData;
use App\Data\Payment\PaymentMethodData;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Refund\Refund;
use App\Models\Refund\RefundStatus;
use App\Enum\Order\RefundStatusEnum;
use App\Events\PaymentEvent;
use App\Models\Payment\PaymentMethod;

class RefundStatusAction
{
    
    protected $refund;
    protected $status;
    protected $request;
    function __construct(Request $request, Refund $refund, $status)
    {
        $this->refund = $refund;
        $this->status = $status;
        $this->request = $request;
    }
    public function updateStatus()
    {
        switch ($this->status) {
            case 'PAID':
                $this->refund->update([
                    'status' => RefundStatusEnum::PAID,
                    'is_new'=>false,
                    'paid_by'=>($this->request->payment_method==1) ? 'ESEWA' : (($this->request->payment_method==2) ? 'KHALTI' : 'COD')
                ]);
                RefundStatus::updateOrCreate([
                    'status'=>RefundStatusEnum::PAID,
                    'refund_id'=>$this->refund->id,
                ], [
                    'date'=>Carbon::now()->toDateString(),
                    'updated_by'=>auth()->user()->id,
                    'remarks'=>$this->request->remarks,
                ]);

                $method  = (new PaymentMethodData())->getSingleMethod($this->request->payment_method);
                $paymentData = (new PaymentHistoryFormData(
                    get_class(auth()->user()->getModel()), 
                    auth()->user()->id,
                    get_class($this->refund->user()->getModel()),
                    $this->refund->user->id,
                    get_class($this->refund->getModel()),
                    $this->refund->id,
                    get_class($method->getModel()),
                    $method->id,
                    $method->type,
                    'Refund Paid',
                    route('returnable.show', $this->refund->returnOrder->id),
                    'Refund Paid To the Customer ',
                    false,
                    true
                ))->getData();
                PaymentEvent::dispatch($paymentData);
                break;
            case 'REJECTED':
                $this->refund->update([
                    'status' => RefundStatusEnum::REJECTED,
                    'is_new'=>false,
                ]);
                RefundStatus::updateOrCreate([
                    'status'=>RefundStatusEnum::REJECTED,
                    'refund_id'=>$this->refund->id,
                ], [
                    'date'=>Carbon::now()->toDateString(),
                    'updated_by'=>auth()->user()->id,
                    'remarks'=>$this->request->remarks,
                ]);
                break;
        }
    }

    public function updateDirectRefundStatus()
    {
        dd($this->status);
        switch ($this->status) {
            case 'PAID':
                $this->refund->update([
                    'status' => RefundStatusEnum::PAID,
                    'is_new'=>false,
                    'paid_by'=>($this->request->payment_method==1) ? 'ESEWA' : (($this->request->payment_method==2) ? 'KHALTI' : 'COD')
                ]);
                RefundStatus::updateOrCreate([
                    'status'=>RefundStatusEnum::PAID,
                    'refund_id'=>$this->refund->id,
                ], [
                    'date'=>Carbon::now()->toDateString(),
                    'updated_by'=>auth()->user()->id,
                    'remarks'=>$this->request->remarks,
                ]);

                $method  = (new PaymentMethodData())->getSingleMethod($this->request->payment_method);
                $paymentData = (new PaymentHistoryFormData(
                    get_class(auth()->user()->getModel()), 
                    auth()->user()->id,
                    get_class($this->refund->user()->getModel()),
                    $this->refund->user->id,
                    get_class($this->refund->getModel()),
                    $this->refund->id,
                    get_class($method->getModel()),
                    $method->id,
                    $method->type,
                    'Refund Paid',
                    route('returnable.show', $this->refund->returnOrder->id),
                    'Refund Paid To the Customer ',
                    false,
                    true
                ))->getData();
                PaymentEvent::dispatch($paymentData);
                break;
            case 'REJECTED':
                $this->refund->update([
                    'status' => RefundStatusEnum::REJECTED,
                    'is_new'=>false,
                ]);
                RefundStatus::updateOrCreate([
                    'status'=>RefundStatusEnum::REJECTED,
                    'refund_id'=>$this->refund->id,
                ], [
                    'date'=>Carbon::now()->toDateString(),
                    'updated_by'=>auth()->user()->id,
                    'remarks'=>$this->request->remarks,
                ]);
                break;
        }
    }

}
