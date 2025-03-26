<?php

namespace App\Actions;

use Carbon\Carbon;
use App\Events\PaymentEvent;
use Illuminate\Http\Request;
use App\Models\Payout\Payout;
use App\Enum\Payment\PayoutEnum;
use App\Models\Payout\PayoutStatus;
use Illuminate\Support\Facades\Auth;
use App\Data\Payment\PaymentMethodData;
use App\Models\Order\Seller\SellerOrder;
use Psy\CodeCleaner\FunctionContextPass;
use App\Data\Form\PaymentHistoryFormData;
use App\Enum\Seller\SellerOrderStatusEnum;
use App\Models\Order\Seller\SellerOrderStatus;
use App\Actions\Notification\NotificationAction;

class SellerPayoutAction
{
    protected $payout;
    protected $status;
    protected $request;

    function __construct(Request $request, Payout $payout, $status)
    {
        $this->payout = $payout;
        $this->status = $status;
        $this->request = $request;
    }

    public function newUpdatedStatus()
    {
        switch ($this->status) {
            case 'NOT_PAID':
                $this->payout->update([

                    'status' => str(PayoutEnum::NOT_RECEIVED),
                    'seller_order_id' => $this->payout->seller_order_id,
                ]);

                $notPaid = PayoutStatus::updateOrCreate([
                    'status' => PayoutEnum::NOT_RECEIVED,
                    'payout_id' => $this->payout->id,
                    'updated_by' => auth()->user()->id,
                    'date' => Carbon::now()->toDateString(),
                ]);
                break;

            case 'REQUESTED':
                $this->payout->update([
                    'status' => str(PayoutEnum::REQUESTED),
                    'seller_order_id' => $this->payout->seller_order_id,
                ]);
                break;
            case 'PROCESSING':
                $this->payout->update([
                    'status' => str(PayoutEnum::PROCESSING),
                    'seller_order_id' => $this->payout->seller_order_id,
                ]);
                $processing = PayoutStatus::updateOrCreate([
                    'status' => PayoutEnum::PROCESSING,
                    'date' => Carbon::now()->toDateString(),
                    'updated_by' => auth()->user()->id,
                    'payout_id' => $this->payout->id,
                ]);
                break;
            case 'APPROVED':
                $this->payout->update([
                    'status' => str(PayoutEnum::APPROVED),
                    'seller_order_id' => $this->payout->seller_order_id,
                ]);
                $payout = PayoutStatus::updateOrCreate([
                    'status' => PayoutEnum::APPROVED,
                    'date' => Carbon::now()->toDateString(),
                    'updated_by' => auth()->user()->id,
                    'payout_id' => $this->payout->id,
                ]);

                break;
            case 'CANCEL':
                $this->payout->update([
                    'status' => str(PayoutEnum::CANCEL),
                    'seller_order_id' => $this->payout->seller_order_id,
                ]);

                $payout = PayoutStatus::updateOrCreate([
                    'status' => str(PayoutEnum::CANCEL),
                    'date' => Carbon::now()->toDateString(),
                    'updated_by' => auth()->user()->id,
                    'payout_id' => $this->payout->id,
                ]);
                break;
            case 'REJECTED':
                $this->payout->update([
                    'status' => str(PayoutEnum::REJECTED),
                    'seller_order_id' => $this->payout->seller_order_id,
                ]);

                $rejected = PayoutStatus::updateOrCreate([
                    'status' => str(PayoutEnum::REJECTED),
                    'updated_by' => auth()->user()->id,
                    'payout_id' => $this->payout->id,
                    'date' => Carbon::now()->toDateString(),

                ]);


                break;
            case 'PAID':
                $this->payout->update([
                    'status' => str(PayoutEnum::RECEIVED),
                    'seller_order_id' => $this->payout->seller_order_id,
                ]);

                $notPaid = PayoutStatus::where('status', PayoutEnum::NOT_RECEIVED)->where('payout_id', $this->payout->id)->first();
                $approved = PayoutStatus::where('status', PayoutEnum::APPROVED)->where('payout_id', $this->payout->id)->first();
                $processing = PayoutStatus::where('status', PayoutEnum::PROCESSING)->where('payout_id', $this->payout->id)->first();

                if (!$notPaid) {
                    PayoutStatus::create([
                        'status' => PayoutEnum::NOT_RECEIVED,
                        'updated_by' => auth()->user()->id,
                        'payout_id' => $this->payout->id,
                        'date' => Carbon::now()->toDateString(),
                    ]);
                }
                if (!$approved) {
                    PayoutStatus::create([
                        'status' => PayoutEnum::APPROVED,
                        'updated_by' => auth()->user()->id,
                        'payout_id' => $this->payout->id,
                        'date' => Carbon::now()->toDateString(),
                    ]);
                }
                if (!$processing) {
                    PayoutStatus::create([
                        'status' => PayoutEnum::PROCESSING,
                        'updated_by' => auth()->user()->id,
                        'payout_id' => $this->payout->id,
                        'date' => Carbon::now()->toDateString(),
                    ]);
                }
                PayoutStatus::updateOrCreate([
                    'status' => PayoutEnum::RECEIVED,
                    'updated_by' => auth()->user()->id,
                    'payout_id' => $this->payout->id,
                    'date' => Carbon::now()->toDateString(),
                ]);

                $method  = (new PaymentMethodData())->getSingleMethod($this->request->payment_method);
                $paymentData = (new PaymentHistoryFormData(
                    get_class(auth()->user()->getModel()),
                    auth()->user()->id,
                    get_class($this->payout->seller()->getModel()),
                    $this->payout->seller->id,
                    get_class($this->payout->getModel()),
                    $this->payout->id,
                    get_class($method->getModel()),
                    $method->id,
                    $method->type,
                    'Payout Paid',
                    route('seller-payout.show', $this->payout->id),
                    'Refund Paid To the Customer ',
                    false,
                    true
                ))->getData();
                PaymentEvent::dispatch($paymentData);
                $notification_data = [
                    'from_model' => get_class(auth()->user()->getModel()),
                    'from_id' => auth()->user()->id,
                    'to_model' => get_class($this->payout->seller()->getModel()) ?? null,
                    'to_id' => $this->payout->seller->id,
                    'title' => 'You Payout has been settled',
                    'summary' => 'Please Show your Payout status',
                    'url' => route('seller-transaction.index'),
                    'is_read' => false,
                ];
                (new NotificationAction($notification_data, 'to-seller'))->store();
                break;

            default:

                break;
        }
    }
}
