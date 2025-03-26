<?php

namespace App\Http\Controllers\Seller\Order;

use DataTables;
use App\Models\Local;
use App\Models\Order;
use App\Models\Seller;
use App\Models\Setting;
use App\Events\LogEvent;
use App\Models\District;
use App\Models\Location;
use App\Models\Province;
use App\Helpers\Utilities;
use App\Models\New_Customer;
use Illuminate\Http\Request;
use App\Models\Payout\Payout;
use BaconQrCode\Encoder\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Enum\Payment\PayoutEnum;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Order\Seller\SellerOrder;
use Illuminate\Support\Facades\Validator;
use App\Data\Seller\SellerOrderStatusData;
use App\Data\Seller\SellerProductBillBarCode;
use App\Actions\Seller\Order\SellerOrderStatusAction;
use App\Actions\SellerOrderCancel\SellerOrderCancelAction;
use App\Http\Controllers\Datatables\SellerTransactionController;
use PhpParser\Node\Stmt\Else_;

class SellerOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware(['permission:seller-order-read'], ['only' => ['index']]);
        $this->middleware(['permission:seller-order-create'], ['only' => ['create', 'store']]);
        $this->middleware(['permission:seller-order-edit'], ['only' => ['edit', 'update']]);
        $this->middleware(['permission:seller-order-delete'], ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $sellerOrder = SellerOrder::Visible()->latest();
            return DataTables::of($sellerOrder)
                ->addIndexColumn()
                ->addColumn('status', function ($row) {
                    // dd($row);
                    $status = '';
                    if ((int)$row->is_new == 1 &&  $row->status == 1) {
                        $status = '<div class="d-flex"><span class="badge bg-primary">Pending</span><span class="badge bg-warning">New</span></div>';
                    } else {
                        $status = $this->getStatus($row->status,$row);
                    }
                    $cancelOrder=$row->sellerProductOrder;
                    $cancelOrder=collect($cancelOrder)->filter(function($item)
                    {
                        if($item->cancel_status=='0')
                        {
                            return $item;
                        }
                    });
                    // dd(count($cancelOrder));
                    if(count($cancelOrder) >0)
                    {
                        $action = '<div class="d-flex">' . $status . '<div class="dropdown"><button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0" data-bs-toggle="dropdown"><i data-feather="more-vertical"></i></button><div class="dropdown-menu dropdown-menu-end">';

                        $action .= $this->getActions($row);
    
                        $action .= '</div></div></div>';
                    }
                    else
                    {
                        $action=$status;
                    }
                    return $action;
                })

                ->addColumn('order_id', function ($row) {
                    return $row->user->name ?? "Not Defined";
                })
                ->addColumn('seller_id', function ($row) {
                    return $row->seller_id ?? "Not Defined";
                })
                ->addColumn('ref_id', function ($row) {
                    return $row->order->ref_id ?? "Not Defined";
                })
                ->addColumn('qty_id', function ($row) {
                    return $row->qty ?? "Not Defined";
                })
                ->addColumn('total_price', function ($row) {
                    return formattedNepaliNumber($row->subtotal) ?? "Not Defined";
                })
                ->addColumn('payment_status', function ($row) {
                    $datas = $row->order->payment_status ?? null;
                    if ($datas == '0') {
                        return "Not Paid";
                    } else {
                        return "paid";
                    }
                })
                ->addColumn('action', function ($row) {
                    $show='';
                    if($row->status==6 || $row->status==4)
                    {
                        $show.='<div class="btn-group"><a href="javascript:;" data-orderId="'.$row->id.'" class="btn btn-sm btn-primary barcode_btn" >View</a>';
                    }elseif($row->status==7)
                    {
                        $show.='<div class="btn-group"><a href="javascript:;" data-orderId="'.$row->id.'" class="btn btn-sm btn-primary cancelReason" data-bs-toggle="modal" data-bs-target="#reasonCancel" >Cancel Reason</a>';
                    }
                    
                    $show.=  Utilities::button(href: route('seller-order.show', $row->id ?? 0), icon: "eye", color: "info", title: 'Show Order');
                    $show.="</div>";
                    return  $show;
                })
                ->rawColumns(['status', 'order_id', 'seller_id', 'ref_id', 'qty_id', 'discount', 'total_price', 'payment_status', 'action'])
                ->make(true);
        }

        LogEvent::dispatch('Order List View', 'Order List View', route('seller-order-index'));

        return view("admin.SellerOrder.index");
    }
    private function getStatus($status,$seller_order)
    {
        $return_status = '';
        switch ($status) {
            case 1:
                $return_status =  '<div class="badge bg-primary">SEEN</div>';
                break;
            case 2:
                $return_status =  '<div class="badge bg-info">READY_TO_SHIP</div>';
                break;
            case 3:
                $return_status =  '<div class="badge bg-info">DISPATCHED</div>';
                break;
            case 4:
                $return_status =  '<div class="badge bg-warning">SHIPPED</div>';
                break;
            case 5:
                $return_status =  '<div class="badge bg-success">DELIVERED TO Customer</div>';
                break;
            case 6:
                // (new SellerProductBillBarCode($seller_order))->generateBill();
                $return_status =  '<div class="badge bg-success">DELIVERED_TO_HUB</div>';
                break;
            case 7:
                $return_status =  '<div class="badge bg-danger">CANCELED BY <span class="text-primary">' . env('APP_NAME') . '</span></div>';
                break;
            case 8:
                $return_status =  '<div class="badge bg-danger">REJECTED <span class="text-primary">' . env('APP_NAME') . '</span></div>';
                break;
            default:
                # code...
                break;
        }
        return $return_status;
    }
    private function getActions($row)
    { {
            $action = '';
            switch ($row->status) {
                case 1:
                    $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="READY_TO_SHIP" data-order_id="' . $row->id . '" href="#"><i data-feather="pie-chart" class="me-50"></i><span>Redy To Ship</span></a>';
                    $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="DISPATCHED" data-order_id="' . $row->id . '" href="#"><i data-feather="truck" class="me-50"></i><span>Dispatched</span></a>';
                    $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="SHIPED" data-order_id="' . $row->id . '" href="#"><i data-feather="truck" class="me-50"></i><span>SHIPPED</span></a>';
                    $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="DELIVERED_TO_HUB" data-order_id="' . $row->id . '" href="#"><i data-feather="target" class="me-50"></i><span>Delivered To Hub</span></a>';
                   
                    break;
                case 2:
                    $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="DISPATCHED" data-order_id="' . $row->id . '" href="#"><i data-feather="truck" class="me-50"></i><span>Dispatched</span></a>';
                    $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="SHIPED" data-order_id="' . $row->id . '" href="#"><i data-feather="truck" class="me-50"></i><span>SHIPPED</span></a>';
                    $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="DELIVERED_TO_HUB" data-order_id="' . $row->id . '" href="#"><i data-feather="target" class="me-50"></i><span>Delivered To Hub</span></a>';
                    break;
                case 3:
                    $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="SHIPED" data-order_id="' . $row->id . '" href="#"><i data-feather="truck" class="me-50"></i><span>SHIPPED</span></a>';
                    $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="DELIVERED_TO_HUB" data-order_id="' . $row->id . '" href="#"><i data-feather="target" class="me-50"></i><span>Delivered To Hub</span></a>';
                    break;
                case 4:
                    $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="DELIVERED_TO_HUB" data-order_id="' . $row->id . '" href="#"><i data-feather="target" class="me-50"></i><span>Delivered To Hub</span></a>';
                    break;
                default:
                    # code...
                    break;
            }
            return $action;
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $sellerOrder = new SellerOrder();
        return view("admin.SellerOrder.form", compact("sellerOrder"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            SellerOrder::create($request->all());
            request()->session()->flash('success', "new SellerOrder created successfully");
            DB::commit();
            return redirect()->route('SellerOrder.index');
        } catch (\Throwable $th) {
            request()->session()->flash('error', $th->getMessage());
            DB::rollback();
            return redirect()->back()->withInput();
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order\Seller\SellerOrder  $sellerOrder
     * @return \Illuminate\Http\Response
     */
    public function show(SellerOrder $sellerOrder, Request $request)
    {
        DB::beginTransaction();
        try {
            $sellerOrder->update([
                'is_new' => false,
            ]);
           if($sellerOrder->is_new==1)
            {
                (new SellerOrderStatusAction($request, $sellerOrder, "SEEN"))->updateStatus();
            }
            $data['sellerOrder'] = $sellerOrder;
                
                DB::commit();
                return view('admin.sellerOrder.show', $data);
            } catch (\Throwable $th) {
                DB::rollBack();
                session()->flash('error', 'Somethinng is wrong');
                return back();
            }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order\Seller\SellerOrder  $sellerOrder
     * @return \Illuminate\Http\Response
     */
    public function edit(SellerOrder $sellerOrder)
    {
        return view("admin.SellerOrder.form", compact("sellerOrder"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order\Seller\SellerOrder  $sellerOrder
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SellerOrder $sellerOrder)
    {
        DB::beginTransaction();
        try {
            $sellerOrder->update($request->all());
            request()->session()->flash('success', "new SellerOrder created successfully");
            DB::commit();
            return redirect()->route('SellerOrder.index');
        } catch (\Throwable $th) {
            request()->session()->flash('error', $th->getMessage());
            DB::rollback();
            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order\Seller\SellerOrder  $sellerOrder
     * @return \Illuminate\Http\Response
     */
    public function destroy(SellerOrder $sellerOrder)
    {
        try {
            $sellerOrder->delete();
            request()->session()->flash('success', "SellerOrder deleted successfully");
            return redirect()->route('SellerOrder.index');
        } catch (\Throwable $th) {
            request()->session()->flash('error', $th->getMessage());
            return redirect()->back()->withInput();
        }
    }


    public function statusAction(Request $request)
    {
        $rules = [
            'order_id' => 'required|exists:seller_orders,id',
            'type' => 'required',
        ];
        if ($request->type == "cancel" || $request->type == "reject") {
            $again_rules = [
                'remarks' => 'required',
            ];
        }

        if($request->type=='cancel')
        {
           (new SellerOrderCancelAction($request))->cancelRequest();
        }
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $validator->errors();
            return back()->withInput()->withErrors($validator->errors());
        }
        DB::beginTransaction();
        try {
            // $st = (new SellerOrderStatusData($request->type))->getStatus();
            $order = SellerOrder::findOrFail($request->order_id);
            (new SellerOrderStatusAction($request, $order, $request->type))->updateStatus();
            session()->flash('success', 'Successfully order has been ' . $request->type);
            DB::commit();
            return back();
        } catch (\Throwable $th) {
            DB::rollBack();
            session()->flash('error', $th->getMessage());
            return redirect()->route('seller-order-index');
        }
    }

    public function generatebarcodeBill(Request $request)
    {
        $seller_order=SellerOrder::findOrfail($request->seller_ordeId);
        $order=Order::findOrFail($seller_order->order_id);
        $seller_order_details=$seller_order->sellerProductOrder;
        $package_wight=null;
        $total_amount=null;
        $qty=null;
        foreach($seller_order_details as $details)
        {
            
            $package_wight+=$details->getProduct->package_weight ?? 0;
            $total_amount+=$details->sub_total ?? 0;
            $qty+=$details->qty ?? 0;
        }
        $image=Setting::first();
        $data['weight']=$package_wight;
        $data['payment_with']=$order->payment_with;
        $data['ref_id']=$order->ref_id;
       
        $data['seller']=Seller::findOrFail($seller_order->seller_id);
       
        
        if(Province::where('id',$data['seller']->province_id)->first())
        {
            $p=Province::where('id',$data['seller']->province_id)->first();
            $data['seller']['province_id']=$p->eng_name ?? null;
        }
        else
        {
            $data['seller']['province_id']=null;
        }

        if(District::where('id',$data['seller']->district_id)->first())
        {
            $d=District::where('id',$data['seller']->district_id)->first();
            $data['seller']['district_id']=$d->np_name ?? null;
        }
        else
        {
            $data['seller']['district_id']=null;
        }
        
        if(Local::where('id',$data['seller']->area)->first())
        
        {
            $a=Local::where('id',$data['seller']->area)->first();
            $data['seller']['area']=$a->local_name ?? null;
        }
        else
        {
            $data['seller']['area']=null;
        }
        
        
        
        if(Location::where('id',$data['seller']->address)->first())
        {
            $l=Location::where('id',$data['seller']->address)->first();
            $data['seller']['address']=$l->title ?? null;

        }
        else
        {
            $data['seller']['address']=null;

        }
        
        $data['user']=New_Customer::findOrFail($seller_order->user_id);
        $data['total']=$total_amount;
        $data['qty']=$qty;
        $data['logo']=$image->value;
    
        $bar_html=route('getSellerOrderdetails',$seller_order->id);
        return view('seller.bill.barcodebill',compact('seller_order','seller_order_details','bar_html','data','order'));
    }
}
