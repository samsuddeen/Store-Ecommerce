<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Order;
use App\Models\Seller;
use App\Models\Setting;
use App\Models\District;
use App\Models\Province;
use App\Helpers\Utilities;
use App\Models\OrderAsset;
use App\Helpers\EmailSetUp;
use App\Models\Task\Action;
use Illuminate\Support\Arr;
use App\Models\New_Customer;
use Illuminate\Http\Request;
use App\Data\Filter\FilterData;
use App\Traits\ApiNotification;
use App\Traits\SellerOrderTrait;
use Illuminate\Support\Facades\DB;
use App\Data\Order\OrderStatusData;
use App\Models\ProductCancelReason;
use App\Http\Controllers\Controller;
use App\Actions\Admin\Order\AdminOrder;
use App\Actions\Order\OrderStatusAction;
use App\Models\Order\Seller\SellerOrder;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use App\Enum\MessageSetup\MessageSetupEnum;
use App\Models\Order\Seller\SellerOrderStatus;
use App\Actions\Notification\NotificationAction;

class OrderController extends Controller
{
    use SellerOrderTrait,ApiNotification;

    public function __construct()
    {
        $this->middleware(['permission:order-read'], ['only' => ['index']]);
    }
    public function status($id)
    {
        $patient = Order::find($id);
        $patient->update([
            'status' => 1,
        ]);
    }
    public function index(Request $request)
    {
        // dd($request->all());
        $filters = (new FilterData($request))->getData();
        $data['filters'] = $filters;
        $retrive_request = '';
        if (count($filters) > 0) {
            $retrive_request = '?';
        }
        foreach ($filters as $index => $filter) {
            $retrive_request .= $index . '=' . $filter;
        }
        $data['retrive_request'] = $retrive_request;
        if (Arr::get($filters, 'type')) {
            if (Arr::get($filters, 'type') == 'seller-order') {
                return view('admin.order.seller-order', $data);
            }
            if (Arr::get($filters, 'type') == 'inhouse-order') {
                return view('admin.order.inhouse-order', $data);
            }
        }
        $data['actions'] = Action::where('status',1)->get();
        $data['users'] = User::role(['staff','delivery'])->where('status',1)->get();
        $data['orders']=(new AdminOrder($data['filters']))->getOrder();
        $data['filterCustomer']=true;
        $data['url']=route('order.index');
        return view('admin.order.index', $data);
    }

    public function apply(Request $request)
    {
        $orders = Order::whereIn('id', $request->order_ids)->get();
        if ($request->action == 'delete') {
            foreach ($orders as $order) {
                $order->delete();
            }
        } elseif ($request->action == 'approve') {
            foreach ($orders as $order) {
                $order->update(['admin_approve' => 1]);
            }
        }
        return response()->json([
            "success" => "success",
        ]);
    }

    public function productDetail(Request $request)
    {
        $ref_id = $request->ref_id;
        $order = Order::where('ref_id', $ref_id)->first();
        $products = $order->orderAssets;
        return view('admin.order.order-product', compact('products'));
    }
    public function viewOrder(Request $request, $ref_id)
    {
        $order = Order::where('ref_id', $ref_id)->first();
        // dd($order);
        DB::beginTransaction();
        try {
            if($order->status!=6 || $order->status==7)
            {
                if ($order->is_new == 0) {
                    (new OrderStatusAction($request, $order, "SEEN"))->updateStatus();
                }
            }
            $order->update([
                'pending' => 0,
                'is_new' => 1,
            ]);
            
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            session()->flash('error', $th->getMessage());
            return redirect('/admin/order');
        }
        $order = Order::where('ref_id', $ref_id)->with('orderAssets')->firstOrFail();
        $data['order'] = $order;
        
        
       $cancelOrderAsset=$data['order']->orderAssets;
       $cancelOrderAsset=collect($cancelOrderAsset)->filter(function($item)
        {
            if($item->cancel_status=='1')
            {
                return $item;
            }
        });
        if($order->user_id=='63'){
            return view('admin.order.view-order-guest', $data)->with('cancelOrderAsset',$cancelOrderAsset);
        }else{
            return view('admin.order.view-order', $data)->with('cancelOrderAsset',$cancelOrderAsset);
        }
    }
    public function statusAction(Request $request)
    {
    
        
        $rules = [
            'order_id' => 'required|exists:orders,id',
            'type' => 'required',
        ];
        if ($request->type == "cancel" || $request->type == "reject") {
            $again_rules = [
                'remarks' => 'required',
            ];
        }
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $validator->errors();
            return back()->withInput()->withErrors($validator->errors());
        }
        DB::beginTransaction();
        try {
            $st = (new OrderStatusData($request->type))->getStatus();
            $order = Order::findOrFail($request->order_id);
            $order->update([
                'pending' => 0,
                'status' => $st['status_value'],
                'is_new'=>1
            ]);
            (new OrderStatusAction($request, $order, $st['status']))->updateStatus();
            $this->apiSendNotification($st['status'],'personal',$order);
            $notification_data = [
                'from_model' => get_class(auth()->user()->getModel()),
                'from_id' => auth()->user()->id,
                'to_model' => get_class($order->user()->getModel()) ?? null,
                'to_id' => $order->user->id,
                'title' => 'You Order has been ' . $request->type,
                'summary' => 'Please Show your order status',
                'url' => route('Corder'),
                'is_read' => false,
            ];
            
            
            EmailSetUp::OrderStatusMail($request->type, $data = $order); 


            session()->flash('success', 'Successfully order has been ' . $request->type);
            DB::commit();
            (new NotificationAction($notification_data))->store();
            return back();
        } catch (\Throwable $th) {
            DB::rollBack();
            session()->flash('error', $th->getMessage());
            return redirect('/admin/order');
        }
    }

    public function inHouseOrder(Request $request)
    {

        $filters = (new FilterData($request))->getData();
        $filters['filter_type'] = 'inhouse-order';

        $data['filters'] = $filters;
        $retrive_request = '';
        if (count($filters) > 0) {
            $retrive_request = '?';
        }
        foreach ($filters as $index => $filter) {

            $retrive_request .= $index . '=' . $filter;
        }

        $data['retrive_request'] = $retrive_request;

        if (Arr::get($filters, 'type')) {
            if (Arr::get($filters, 'type') == 'seller-order') {
                return view('admin.order.seller-order', $data);
            }
            if (Arr::get($filters, 'type') == 'inhouse-order') {
                return view('admin.order.inhouse-order', $data);
            }
        }
        return view('admin.order.inhouse-order', $data);
    }

    public function sellerOrderView(request $request)
    {
        $filters = (new FilterData($request))->getData();
        $filters['filter_type'] = 'inhouse-order';

        $data['filters'] = $filters;
        $retrive_request = '';
        if (count($filters) > 0) {
            $retrive_request = '?';
        }
        foreach ($filters as $index => $filter) {

            $retrive_request .= $index . '=' . $filter;
        }

        $data['retrive_request'] = $retrive_request;


        return view('admin.order.seller-list', $data);
    }

    public function sellerOrderViewList(request $request, $id)
    {
        $seller = Seller::findOrFail($id);

        $filters = (new FilterData($request))->getData();
        $filters['filter_type'] = 'inhouse-order';

        $data['filters'] = $filters;
        $retrive_request = '';
        if (count($filters) > 0) {
            $retrive_request = '?';
        }
        foreach ($filters as $index => $filter) {

            $retrive_request .= $index . '=' . $filter;
        }

        $data['retrive_request'] = $retrive_request;
        $data['seller_id'] = $seller->id;
        return view('admin.order.seller-order-view', $data);
    }

    public function alldeliveryIndex(Request $request)
    {

        $filters = (new FilterData($request))->getData();
        $filters['filter_type'] = 'inhouse-order';

        $data['filters'] = $filters;
        $retrive_request = '';
        if (count($filters) > 0) {
            $retrive_request = '?';
        }
        foreach ($filters as $index => $filter) {

            $retrive_request .= $index . '=' . $filter;
        }

        $data['retrive_request'] = $retrive_request;


        return view('admin.order.alldelivery', $data);
    }

    public function generateBarCode(Request $request)
    {
        $image=Setting::first();
        $data['logo']=$image->value;

        $order=Order::where('id',$request->orderId)->first();
        
        $order_product=$order->orderAssets;

        $total_amount=null;
        $total_qty=null;
        $total_discount=null;
        $total_weight=null;
        foreach($order_product as $product)
        {
            // dd($product->product);
            $total_amount=$total_amount+($product->sub_total_price ?? 0);
            $total_qty=$total_qty+($product->qty ?? 0);
            $total_weight=$total_weight+($product->product->package_weight ?? 0);
        }
        
        $data['payment_with']=$order->payment_with ?? null;
        $data['weight']=$total_weight;
        $data['ref_id']=$order->ref_id;
        if($order->user_id===1)
        {
            $provinceData=Province::find($order->province);
            $districtData=District::find($order->district);
            $data['user_name']=$order->name;
            $data['user_email']=$order->email;
            $data['user_province']=$provinceData->eng_name ?? '';
            $data['user_district']=$districtData->np_name;
            $data['user_address']=$order->address;
            $data['user_phone']=$order->phone;
        }
        else
        {
            
            $user=New_Customer::findOrFail($order->user_id);
            
            $provinceData=Province::find($user->province);
            $districtData=District::find($user->district);
            $data['user_name']=$user->name;
            $data['user_email']=$user->email;
            $data['user_province']=$provinceData->eng_name ?? '';
            $data['user_district']=$districtData->np_name ?? '';
            $data['user_address']=$user->area;
            $data['user_phone']=$order->phone;

        }
        
        $data['total']=$total_amount -round($order->coupon_discount_price ?? 0);
        $data['qty']=$total_qty;
        $data['logo']=$image->value;
        $data['order']=$order;

        // $seller_order=SellerOrder::where('order_id',$order->id)->first();
        $bar_html=route('getAdminOrderdetails',$data['order']->id);
      
        return view('admin.billgenerate',compact('order','data','bar_html'));
        
    }
    public function getAdminOrderdetails(Request $request,$orderStatus)
    {
       
        $seller_order=SellerOrder::findOrFail($orderStatus);
        $order=Order::findOrFail($seller_order->order_id);
        $orderAsset=$order->orderAssets;
        $seller_order_details=$seller_order->sellerProductOrder;
        $seller_details=Seller::findOrFail($seller_order->seller_id);
        $order_status = SellerOrderStatus::where('seller_order_id', $seller_order->id)->get();
        $order_status = collect($order_status)->map(function ($item) {
            return [
                'created_at' => $item->created_at,
                'status_value' => ($item->status == 1) ? 'SEEN' : (($item->status == 2) ? 'READY_TO_SHIP' : (($item->status == 3) ? 'DISPATCHED' : (($item->status == 4) ? 'SHIPED' : (($item->status == 5) ? 'DELIVERED' : (($item->status == 6) ? 'DELIVERED_TO_HUB' : (($item->status == 6) ? 'CANCELED' : 'REJECTED'))))))
            ];
        });
        $admin=true;
        return view('seller.bill.billdetailadmin',compact('seller_order','orderAsset','seller_order_details','seller_details','order','order_status','admin'));
    
    }

    public function orderCancelRequest(Request $request)
    {
        if ($request->ajax()) {
            $cancelProduct=ProductCancelReason::orderBy('created_at','DESC')->get();
            return DataTables::of($cancelProduct)
                ->addIndexColumn()
                ->addColumn('customer', function ($row) {
                    return $row->user->name ?? "Not Defined";
                })
                ->addColumn('reason', function ($row) {
                    return $row->reason ?? "Not Defined";
                })

                ->addColumn('action', function ($row) {
                    $show='';
                    $show.=  Utilities::button(href: url('admin/view-order',$row->order->ref_id ?? 0), icon: "eye", color: "info", title: 'Show Order');
                    $show.="</div>";
                    return  $show;
                })
                ->rawColumns([ 'customer', 'reason','action'])
                ->make(true);
        }
        $filters = (new FilterData($request))->getData();
        $data['filters']  = $filters;
        return view('admin.order.cancelOrder',$data);
    }

    public function getCustomerOrder(Request $request,$id)
    {
        $customer=New_Customer::findOrFail($id);
        $filters = (new FilterData($request))->getData($id);
        $data['filters'] = $filters;
        $retrive_request = '';
        if (count($filters) > 0) {
            $retrive_request = '?';
        }
        foreach ($filters as $index => $filter) {
            $retrive_request .= $index . '=' . $filter;
        }
        $data['retrive_request'] = $retrive_request;
        if (Arr::get($filters, 'type')) {
            if (Arr::get($filters, 'type') == 'seller-order') {
                return view('admin.order.seller-order', $data);
            }
            if (Arr::get($filters, 'type') == 'inhouse-order') {
                return view('admin.order.inhouse-order', $data);
            }
        }
        $data['actions'] = Action::where('status',1)->get();
        $data['users'] = User::role(['staff','delivery'])->where('status',1)->get();
        $data['orders']=(new AdminOrder($data['filters']))->getOrder();
        $data['filterCustomer']=false;
        $data['url']=route('admin.customerallorder',$id);
        $data['customerName']=$customer->name;
        return view('admin.order.index', $data);
    }
    
}
