<?php

namespace App\Datatables;

use App\Enum\Payment\PayoutEnum;
use App\Models\Tag;
use App\Helpers\Utilities;
use App\Models\Setting\PayoutSetting;
use App\Enum\Setting\PayoutPeriodEnum;
use App\Models\Order\Seller\SellerOrder;
use App\Models\Payout\Payout;
use Yajra\DataTables\Facades\DataTables;

class SellerTransactionDatatables implements DatatablesInterface
{

    public function getData()
    {
        $seller=\Auth::guard('seller')->user();
       
        $sellerOrder = SellerOrder::where('seller_id',$seller->id)->transaction()->latest();
      
        return DataTables::of($sellerOrder)
            ->addIndexColumn()
            ->addColumn('order_id', function ($row) {
                return $row->user->name ?? "Not Defined";
            })
            ->addColumn('seller_id', function ($row) {
                return '<a href="#">' . $row->seller_id . '</a>' ?? "Not Defined";
            })
            ->addColumn('ref_id', function ($row) {
                return '<a href="#">' . $row->order->ref_id . '</a>' ?? "Not Defined";
            })
            ->addColumn('qty_id', function ($row) {
                return $row->qty ?? "Not Defined";
            })
            ->addColumn('total_price', function ($row) {
                return '$. ' . formattedNepaliNumber($row->total) ?? "Not Defined";
            })
            ->addColumn('payment_status', function ($row) {

                $status = $this->getStatus($row->payment_status);
                $action = '<div class="d-flex">' . $status . '<div class="dropdown"><button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0" data-bs-toggle="dropdown"><i data-feather="more-vertical"></i></button><div class="dropdown-menu dropdown-menu-end">';
                $action .= $this->getActions($row);
                $action .= '</div></div></div>';
                return $action;
            })
            ->addColumn('action', function ($row) {
                $show =  Utilities::button(href: route('seller-order.show', $row->id ?? 0), icon: "eye", color: "info", title: 'Show Order');
                return  $show;
            })
            ->rawColumns(['status', 'order_id', 'seller_id', 'ref_id', 'qty_id', 'discount', 'total_price', 'payment_status', 'action'])
            ->make(true);
    }
    private function getStatus($status)
    {
        $return_status = '';
        switch ($status) {
            case PayoutEnum::PENDING:
                $return_status =  '<div class="badge bg-danger">PENDING</div>';
                break;
            case PayoutEnum::NOT_RECEIVED:
                $return_status =  '<div class="badge bg-primary">NOT_RECEIVED</div>';
                break;
            case PayoutEnum::RECEIVED:
                $return_status =  '<div class="badge bg-success">RECEIVED</div>';
                break;
            case PayoutEnum::REQUESTED:
                $return_status =  '<div class="badge bg-info">REQUESTED</div>';
                break;
            case PayoutEnum::PROCESSING:
                $return_status =  '<div class="badge bg-secondary">PROCESSING</div>';
                break;
            case PayoutEnum::APPROVED:
                $return_status =  '<div class="badge bg-success">APPROVED</div>';
                break;
            case PayoutEnum::CANCEL:
                $return_status =  '<div class="badge bg-warning">CANCEL</div>';
                break;
            case PayoutEnum::REJECTED:
                $return_status =  '<div class="badge bg-success">REJECTED</div>';
                break;
            default:
                # code...
                break;
        }
        return $return_status;
    }
    private function getActions($row)
    {
        $action = '';
        if(((int)$row->payment_status == 0)){
            $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="request" data-order_id="' . $row->id . '" href="#"><i data-feather="pie-chart" class="me-50"></i><span>Request for Payment</span></a>';
        }
        return $action;
    }
}
