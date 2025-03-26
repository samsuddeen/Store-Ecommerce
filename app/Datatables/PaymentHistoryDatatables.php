<?php

namespace App\Datatables;

use App\Data\Payment\PaymentHistoryData;
use App\Enum\Payment\PaymentTypeEnum;
use App\Enum\Payment\PayoutEnum;
use App\Helpers\Utilities;
use App\Models\Payment\PaymentHistory;
use Yajra\DataTables\Facades\DataTables;

class PaymentHistoryDatatables implements DatatablesInterface
{
    protected $filters;
    function __construct($filters = [])
    {
        $this->filters = $filters;
    }
    public function getData()
    {
        $payment_histories = (new PaymentHistoryData($this->filters))->getData();
        return DataTables::of($payment_histories)
            ->addIndexColumn()
            ->addColumn('date', function ($row) {
                return $row->created_at->format('d M, Y');
            })
            ->addColumn('paid_by', function ($row) {
                return $this->getPaymentName($row, 'from_model', 'from_id');
            })
            ->addColumn('received_by', function ($row) {
                return $this->getPaymentName($row, 'to_model', 'to_id');
            })
            ->addColumn('amount', function ($row) {
                return $this->getAmount($row);
            })

            ->addColumn('status', function ($row) {
                return $this->getStatus($row->is_received);
            })
            ->addColumn('method', function ($row) {
                return $this->getMethod($row);
            })
            // ->addColumn('action', function ($row) {
            //     $show =  Utilities::button(href: route('seller-order.show', $row->id ?? 0), icon: "eye", color: "info", title: 'Show Order');
            //     return  $show;
            // })
            ->rawColumns(['paid_by', 'received_by', 'amount', 'status', 'method'])
            ->make(true);
    }
    private function getPaymentName(PaymentHistory $paymentHistory, $model_name, $model_id)
    {
        $paidFrom = $paymentHistory->$model_name::find($paymentHistory->$model_id);
        $paid_by = "";
        switch ($paymentHistory->$model_name) {
            case 'App\Models\User':
                if ($paidFrom) {
                    if ($paidFrom->isSuperAdmin()) {
                        $paid_by = "<a href=".route('user.show', $paidFrom->id).">".$paidFrom->name . " [Super Admin]</a>";
                    } elseif ($paidFrom->isAdmin()) {
                        $paid_by = "<a href=".route('user.show', $paidFrom->id).">".$paidFrom->name . " [Admin]</a>";
                    } else {
                        $paid_by = "<a href=".route('user.show', $paidFrom->id).">".$paidFrom->name . '[' . $paidFrom->getRoleName() . ']</a>';
                    }
                }
                break;
            case 'App\Models\Seller':
                if ($paidFrom) {
                    $paid_by = '<a href='.route('seller.show', $paidFrom->id ?? 0).'>'.$paidFrom->name . '[Seller]</a>';
                }
                break;
            case 'App\Models\New_Customer':
                if ($paidFrom) {
                    $paid_by = '<a href='.route('customer.show', $paidFrom->id ?? 0).'>'.$paidFrom->name . '[Customer]</a>';
                }
                break;

            default:
                # code...
                break;
        }
        return $paid_by;
    }

    private function getAmount(PaymentHistory $paymentHistory)
    {
        $reason = $paymentHistory->reason_model::find($paymentHistory->reason_id);
        $amount = "Rs ";
        switch ($paymentHistory->reason_model) {
            case 'App\Models\Refund\Refund':
                if($reason){
                    $amount .=formattedNepaliNumber($reason->returnOrder->amount);
                }else{
                    $amount .="0";
                }
                break;
            case 'App\Models\Order':
                if($reason){
                    $amount .=formattedNepaliNumber($reason->total_price);
                }else{
                    $amount .="0";
                }
                break;
            case 'App\Models\Order\Seller\SellerOrder':
                if($reason){
                    $amount .=formattedNepaliNumber($reason->total);
                }else{
                    $amount .="0";
                }
                break;
            case 'App\Models\Payout\Payout':
                if($reason){
                    $amount .=formattedNepaliNumber($reason->transaction->total);
                }else{
                    $amount .="0";
                }
                break;
            default:
                # code...
                break;
        }

        return $amount;
       
    }
    private function getStatus($status)
    {
        $return_status = '';
        switch ($status) {
            case 0:
                $return_status =  '<div class="badge bg-danger">PAID</div>';
                break;
            case 1:
                $return_status =  '<div class="badge bg-success">RECEIVED</div>';
                break;
            default:

                break;
        }
        return $return_status;
    }

    private function getMethod(PaymentHistory $paymentHistory)
    {
        if($paymentHistory->method_model !== null){
            $payment_method = $paymentHistory->method_model::find($paymentHistory->method_id);
            if($payment_method){
                return $payment_method->title." [".$this->paymentType($payment_method->type)."]";
            }
        }else{
           return $this->paymentType($paymentHistory->method);
        }
    }
    private function paymentType($method)
    {   
        $method = "";
        switch ($method) {
            case PaymentTypeEnum::BANK:
                $method = 'Bank';
                break;
            default:
                $method = 'Walet';
                break;
        }
        return $method;
    }
}
