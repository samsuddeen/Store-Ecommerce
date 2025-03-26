<?php

namespace App\Traits;

use App\Models\Order;
use App\Models\OrderAsset;
use App\Models\Product;
use App\Models\UserPaymentId;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

trait SellerOrderTrait
{
    public function readyship($ref_id)
    {
        $products = Order::where('ref_id', $ref_id)->get();

        if (!$products->isEmpty()) {
            $user_id = '1';

            foreach ($products as $row) {
                    foreach($row->orderAssets as $datas){
                        $user_check = Product::where('id',$datas->product_id)->select('user_id')->first();
                        
                        if($user_check->user_id == $user_id){
                            $data = [
                                'ready_to_ship' =>'1',
                                'pending'=>'0'
                            ];
                             Order::where('id',$row->id)->update($data);
                            
                            return redirect()->back()->with('success', 'Ready For Shipping Is Set !!!');
                        }else{
                            abort(404);
                        } 
                    
                    }
                }
        }
       
    }
    public function cancelledOrder($ref_id)
    {
        $products = Order::where('ref_id', $ref_id)->get();
        if (!$products->isEmpty()) {
            $user_id = '1';

            foreach ($products as $row) {
                foreach ($row->orderAssets as $datas) {
                    $user_check = Product::where('id', $datas->product_id)->select('user_id')->first();

                    if ($user_check->user_id == $user_id) {
                        $data = [
                            'cancelled' => '1',

                        ];
                        Order::where('id', $row->id)->update($data);
                    } else {
                        abort(404);
                    }
                }
            }
        }
    }
}