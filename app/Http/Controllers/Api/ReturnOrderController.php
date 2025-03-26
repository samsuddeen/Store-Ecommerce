<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Order;
use App\Models\seller;
use App\Models\Product;
use App\Models\Setting;
use App\Events\LogEvent;
use App\Models\OrderAsset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Enum\Order\OrderStatusEnum;
use App\Http\Controllers\Controller;
use App\Models\Customer\ReturnOrder;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ApiReturnOrderRequest;
use App\Actions\Notification\NotificationAction;
use App\Models\Delivery\DeliveryFeedback;
use App\Models\RewardSection;
use App\Models\UserShareList;

class ReturnOrderController extends Controller
{
    public function returnOrder(ApiReturnOrderRequest $request)
    {
        $orderAsset = OrderAsset::findOrFail($request->id);
        $customer_email = \Auth::user();   
        $product = Product::where('id', $orderAsset->product_id)->first();        
        $customer = auth()->guard('customer')->user();   
        $setting = Setting::get();            
        
        
        $data = [
            'product_id'=>$orderAsset->product_id,
            'order_asset_id'=>$orderAsset->id,
            'amount'=> $orderAsset->price,
            'reason'=> $request->reason,
            'comment'=>$request->comment,
            'email'=>$customer_email,
            'setting'=> $setting[0]['value'],
            'admin_email'=>env('MAIL_FROM_ADDRESS'),
            'customer'=> $customer,
            'product'  => $product,
            'qty'=>$request->no_of_item ?? 1,
        ];  
                  

        // $this->sendMailtoAdmin($data);
        // $this->sendMailtoCustomer($data);
        DB::beginTransaction();
        try {

            LogEvent::dispatch('Order Returning', 'Order Returning', route('return.product', $request->id));     
            ReturnOrder::create([
                'product_id'=>$data['product_id'] ?? null,
                'order_asset_id'=>$data['order_asset_id'],
                'amount'=>$orderAsset->price * $data['qty'] ?? 1,
                'reason'=>$data['reason'] ?? null,
                'comment'=>$data['comment'] ??  null,
                'user_id'=>$customer_email->id ?? null,
                'status'=>1,
                'qty'=>$data['qty']
            ]);

            

            $notification_data = [
                'from_model'=>get_class(\Auth::user()->getModel()),
                'from_id'=>\Auth::user()->id,
                'to_model'=> ($product->seller_id) ? get_class(seller::getModel()) : get_class(User::getModel()),
                'to_id'=>$product->seller_id ?? $product->user_id,
                'title'=>'New Order',
                'summary'=>'You Have New Order Request',
                'url'=>route('return.product', $request->id),
                'is_read'=>false,
            ];
            (new NotificationAction($notification_data))->store();

            DB::commit();
            $response=[
                'error'=>false,
                'msg'=>'Return Order Request Success !!'
            ];
            return response()->json($response,200);
            
           
        } catch (\Throwable $th) {
            DB::rollBack();
            $response=[
                'error'=>true,
                'msg'=>'Something Went Wrong !!'
            ];
            return response()->json($response,200);
        }                
    }

    public function getCompletedOrder()
    {
        $returningProducts = ReturnOrder::orderByDesc('created_at')->get();
        $user=\Auth::user();
        $orders = Order::where('user_id', $user->id)->where('pending', '0')->where('status', OrderStatusEnum::DELIVERED)->where('deleted_by_customer', '0')->orderBy('created_at','DESC')->get();

        $current_date = strtotime(date('Y-m-d'));
        $no_days = 15;
        $dataValue=[];
        $orderData=[];
        foreach($orders as $order)
        {
            // $oprderValue=$order;
            if(count($order->orderAssets) >0)
            {
                foreach($order->orderAssets as $asset)
                {
                    $asset->setAttribute('orderStatus',$order->status);
                    $orderData[]=$asset;
                }
            }

            $feedback_exists = DeliveryFeedback::where('order_id',$order->id)->where('customer_id',Auth::user()->id)->first();
            if($feedback_exists)
            {
                $response = [
                    'status' => 200,
                    'error' => false, 
                    'msg' => 'You have already reviewed this order',
                    'data' => $feedback_exists
                ];
    
                return response()->json($response);
            }
        }

        $finalData=[];
        foreach($orderData as $product)
        {
            
            $product->setAttribute('statusData',false);
            $product->image = productImage($product);
            if($product->product !=null)
            {
                if (strtotime(date('Y-m-d', strtotime($product->created_at . '+' . $product->product->returnable_time . ' days'))) >=$current_date && $product->orderStatus === '5' && $product->getReturnOrder === null)
                {
                    
                    $product->setAttribute('statusData',true);
                    
                }

              
            }

            
            
            $finalData[]=$product;
            
            
        }

        $response=[
            'error'=>false,
            'data'=>$finalData,
            'msg'=>'Completed Order'
        ];

        return response()->json($response,200);
    }

    public function getReturnOrderData(Request $request)
    {
        $user=Auth::user();
        $returningProducts = ReturnOrder::orderByDesc('created_at')->where('user_id',$user->id)->get();  
        $returningProducts=collect($returningProducts)->map(function($item)
        {
            $item->name=$item->getProduct->name ?? '';
            
            $item->image=productDetailImage($item->getProduct);
            $item->applyRefundStatus=false;
            $item->refundStatusValue=null;
            $item->refundMessageValue=null;
            $item->returnStatusValue=($item->status=='1') ? 'PENDING' : (($item->status=='2')? 'APPROVED' : (($item->status=='3') ? 'RETURNED' : (($item->status=='4') ? 'REJECTED' : 'RECEIVED')));
            if((int)$item->status === 5 && $item->refundData===null)
            {
                $item->applyRefundStatus=true;
            }

            if((int)$item->status === 5 && $item->refundData!=null && $item->refundData->status==='1')
            {
                $item->refundStatusValue='Refund Pending';
            }elseif((int)$item->status === 5 && $item->refundData!=null && $item->refundData->status==='2')
            {
                $item->refundStatusValue='Refund Paid';
            }elseif((int)$item->status === 5 && $item->refundData!=null && $item->refundData->status==='3')
            {
                $item->refundStatusValue='Refund Rejected';
                $item->refundMessageValue=$item->refundData->orderStatus->remarks ?? '';
            }
            $item->makeHidden(['getProduct','refundData']);
            return $item;
        });

       
        // dd($returningProducts);
        $response=[
            'error'=>false,
            'data'=>$returningProducts,
            'msg'=>'Return Order List'
        ];

        return response()->json($response,200);
    }

    public function userReward()
    {
        $user=Auth::user();
        $rewardData=UserShareList::where('share_from',$user->id)->get();
        $rewardPointTable=RewardSection::first();
        
        $data=[
            'totalShare'=>count($rewardData),
            'totalPoints'=>$rewardData->sum('points'),
            'pointsValue'=>$rewardPointTable->points ?? 0
        ];

        $response=[
            'error'=>false,
            'data'=>$data,
            'msg'=>'User Rewards Details'
        ];
        return response()->json($response,200);
    }
}
