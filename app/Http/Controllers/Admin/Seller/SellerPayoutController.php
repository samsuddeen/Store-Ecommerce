<?php

namespace App\Http\Controllers\Admin\Seller;

use DataTables;
use Illuminate\Http\Request;
use App\Models\Payout\Payout;
use App\Data\Filter\FilterData;
use App\Data\Seller\SellerData;
use App\Enum\Payment\PayoutEnum;
use Illuminate\Support\Facades\DB;
use App\Actions\SellerPayoutAction;
use App\Models\Payout\PayoutStatus;
use App\Actions\Seller\SellerAction;
use App\Http\Controllers\Controller;
use App\Data\Payment\PaymentMethodData;
use App\Models\Order\Seller\SellerOrder;
use Illuminate\Support\Facades\Validator;

class SellerPayoutController extends Controller
{
    public function index(Request $request)
    {

       $payout = PayoutStatus::first();
        $datas = $payout->Payout->seller_id ?? null;
        // dd($datas);
        $datas = (new FilterData($request))->getData();
        $datas['filter'] = $datas;
        $data['filters'] = (new SellerData($datas))->payoutSeller();
        $data['payment_methods'] = (new PaymentMethodData())->getData();


    if($request->ajax()){
        $sellerOrder = Payout::latest();
        return DataTables::of($sellerOrder)
        ->addIndexColumn()

        ->addColumn('seller_id',function($row){
            return $row->seller->name ?? "Not Defined";
        })
        ->addColumn('seller_order_id',function($row){
               return $row->seller_order_id ;
        })
        ->addColumn('status',function($row){
            $status = '';
            $status = $this->getStatus($row->status);
            $action = '<div class="d-flex">' . $status . '<div class="dropdown"><button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0" data-bs-toggle="dropdown"><i data-feather="more-vertical"></i></button><div class="dropdown-menu dropdown-menu-end">';
            $action .=$this->getActions($row);
            $action .= '</div></div></div>';
            return $action;

     })
        ->rawColumns(['seller_id','seller_order_id','status'])
        ->make(true);
    }   

    return view("admin.seller.payout.index",$data);
}

private function getStatus($status)
{
    $return_status = '';
    switch ($status) {
        case 1:
            $return_status =  '<div class="badge bg-primary">NOT_PAID</div>';
            break;
        case 2:
            $return_status =  '<div class="badge bg-info">PAID</div>';
            break;
        case 3:
            $return_status =  '<div class="badge bg-info">REQUESTED</div>';
            break;
        case 4:
            $return_status =  '<div class="badge bg-warning">PROCESSING</div>';
            break;
        case 5:
            $return_status =  '<div class="badge bg-success">APPROVED</div>';
            break;
        case 6:
            $return_status =  '<div class="badge bg-success">CANCEL</div>';
            break;
        case 7:
            $return_status =  '<div class="badge bg-danger">REJECTED</div>';
            break;
        default:
            # code...
            break;
    }
    return $return_status;
}
private function getActions($row)
{
    {
        $action = '';
        switch ($row->status) {
            case PayoutEnum::PENDING:
                $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="PAID" data-order_id="'.$row->id.'" href="#" data-amount="'.$row->transaction->total.'"><i data-feather="truck" class="me-50"></i><span>Pay</span></a>';
                $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="PROCESSING" data-order_id="'.$row->id.'" href="#"><i data-feather="target" class="me-50"></i><span>PROCESSING</span></a>';
                $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="APPROVED" data-order_id="'.$row->id.'" href="#"><i data-feather="target" class="me-50"></i><span>APPROVED</span></a>';
                $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="REJECTED" data-order_id="'.$row->id.'" href="#"><i data-feather="target" class="me-50"></i><span>REJECTED</span></a>';
                $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="CANCEL" data-order_id="'.$row->id.'" href="#"><i data-feather="target" class="me-50"></i><span>CANCEL</span></a>';
                break;
            case PayoutEnum::NOT_RECEIVED:
                $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="PAID" data-order_id="'.$row->id.'" href="#" data-amount="'.$row->transaction->total.'"><i data-feather="truck" class="me-50"></i><span>Pay</span></a>';
                $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="PROCESSING" data-order_id="'.$row->id.'" href="#"><i data-feather="target" class="me-50"></i><span>PROCESSING</span></a>';
                $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="APPROVED" data-order_id="'.$row->id.'" href="#"><i data-feather="target" class="me-50"></i><span>APPROVED</span></a>';
                $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="CANCEL" data-order_id="'.$row->id.'" href="#"><i data-feather="target" class="me-50"></i><span>CANCEL</span></a>';
                $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="REJECTED" data-order_id="'.$row->id.'" href="#"><i data-feather="target" class="me-50"></i><span>REJECTED</span></a>';
                break;
            case PayoutEnum::REQUESTED:      
                $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="PAID" data-order_id="'.$row->id.'" href="#" data-amount="'.$row->transaction->total.'"><i data-feather="truck" class="me-50"></i><span>Pay</span></a>';         
                $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="PAID" data-order_id="'.$row->id.'" href="#"><i data-feather="truck" class="me-50"></i><span>PAID</span></a>';
                $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="PROCESSING" data-order_id="'.$row->id.'" href="#"><i data-feather="target" class="me-50"></i><span>PROCESSING</span></a>';
                $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="APPROVED" data-order_id="'.$row->id.'" href="#"><i data-feather="target" class="me-50"></i><span>APPROVED</span></a>';
                $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="CANCEL" data-order_id="'.$row->id.'" href="#"><i data-feather="target" class="me-50"></i><span>CANCEL</span></a>';
                $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="REJECTED" data-order_id="'.$row->id.'" href="#"><i data-feather="target" class="me-50"></i><span>REJECTED</span></a>';
                break;
            case PayoutEnum::PROCESSING:      
                $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="PAID" data-order_id="'.$row->id.'" href="#" data-amount="'.$row->total.'"><i data-feather="truck" class="me-50"></i><span>Pay</span></a>';         
                $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="PAID" data-order_id="'.$row->id.'" href="#"><i data-feather="truck" class="me-50"></i><span>PAID</span></a>';
                $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="NOT_PAID" data-order_id="'.$row->id.'" href="#"><i data-feather="pie-chart" class="me-50"></i><span>NOT_PAID</span></a>';
                $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="CANCEL" data-order_id="'.$row->id.'" href="#"><i data-feather="target" class="me-50"></i><span>CANCEL</span></a>';
                $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="REJECTED" data-order_id="'.$row->id.'" href="#"><i data-feather="target" class="me-50"></i><span>REJECTED</span></a>';
                break;

                default:
                # code...
                break;
        }
        return $action;
    }
}

    public function statusAction(Request $request)
    {   

        // dd($request->all());
        // $order = Payout::findOrFail($request->order_id);
        
        // $datas = (new SellerPayoutAction($request , $order ,$request->type))->newUpdatedStatus();
        // dd($datas);
        $rules = [
            'payout_id'=>'required|exists:payouts,id',
            'type'=>'required',
        ];
        $validator = Validator::make($request->all(),$rules);
        if($validator->fails()){
            return $validator->errors();
            return back()->withInput()->with('errors',$validator->errors());
        }

        DB::beginTransaction();
        try{
            $payout = Payout::findOrFail($request->payout_id);
            (new SellerPayoutAction($request , $payout ,$request->type))->newUpdatedStatus();
            session()->flash('success', 'Successfully Status has been '.$request->type);
            DB::commit();
            return redirect()->route('seller-payouts');
        }catch(\Throwable $th){
            DB::rollback();
            session()->flash('error', $th->getMessage());
            return redirect()->route('seller-order-index');
        }

    }
}