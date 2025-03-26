<?php

namespace App\Http\Controllers\Customer;

use App\Models\User;
use App\Models\seller;
use App\Models\Product;
use App\Models\Setting;
use App\Events\LogEvent;
use App\Models\OrderAsset;
use Illuminate\Support\Env;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Customer\ReturnOrder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\Admin\Returned\ReturnedStatus;
use App\Actions\Notification\NotificationAction;

class ReturnController extends Controller
{
    public function return(){
        $user=Auth::guard('customer')->user();
        $returningProducts = ReturnOrder::orderByDesc('created_at')->where('user_id',$user->id)->paginate(10);           
        return view('frontend.customer.return', compact('returningProducts'));
    }

    public function getreturnrejected(Request $request)
    {
        $reason=ReturnedStatus::where('return_id',$request->orderId)->first();
        $response=[
            'error'=>false,
            'data'=>$reason->remarks ?? null,
        ];
        return response()->json($response,200);
    }
    public function returnProduct(Request $request, $id){                      
        $orderAsset = OrderAsset::findOrFail($id);
        $customer_email = Auth::guard('customer')->user()->email;        
        $product = Product::where('id', $orderAsset->product_id)->first();        
        $customer = auth()->guard('customer')->user();   
        $setting = Setting::get();            
        $request->validate([
            'reason'=>'required',
            'comment'=>'required',
            'aggree'=>'required',
        ]);
        
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

            LogEvent::dispatch('Order Returning', 'Order Returning', route('return.product', $id));       
            ReturnOrder::create([
                'product_id'=>$data['product_id'] ?? null,
                'order_asset_id'=>$data['order_asset_id'],
                'amount'=>$orderAsset->price * $data['qty'] ?? 1,
                'reason'=>$data['reason'] ?? null,
                'comment'=>$data['comment'] ??  null,
                'user_id'=>auth()->guard('customer')->user()->id ?? null,
                'status'=>1,
                'qty'=>$data['qty']
            ]);

            

            $notification_data = [
                'from_model'=>get_class(auth()->guard('customer')->user()->getModel()),
                'from_id'=>auth()->guard('customer')->user()->id,
                'to_model'=> ($product->seller_id) ? get_class(seller::getModel()) : get_class(User::getModel()),
                'to_id'=>$product->seller_id ?? $product->user_id,
                'title'=>'New Order',
                'summary'=>'You Have New Order Request',
                'url'=>route('return.product', $id),
                'is_read'=>false,
            ];
            (new NotificationAction($notification_data))->store();

            DB::commit();
            $request->session()->flash('success', 'returning Proccess is started, We will Soon Contact You.');
            return redirect()->route('return');
        } catch (\Throwable $th) {
            DB::rollBack();
            $request->session()->flash('error', 'OOPs, Please Try Again');
            return back();
        }                
    }
    
    public function sendMailtoAdmin($data)
    {
        Mail::send('email.return-product.admin', $data, function($message) use($data){
            $message->subject('Product Returning From Customer');
            $message->from($data['email']);
            $message->to($data['admin_email']);
        });
    }

    public function sendMailtoCustomer($data)
    {
        Mail::send('email.return-product.customer', $data, function($message) use($data){
            $message->subject('Product Returning');
            $message->from($data['admin_email']);
            $message->to($data['email']);
        });
    }
}
