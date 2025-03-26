<?php
namespace App\Actions\OrderCancel;

use Illuminate\Http\Request;
use App\Models\SellerOrderCancel;
use App\Enum\Seller\SellerOrderCancelStatus;
use App\Models\Order\Seller\ProductSellerOrder;
use App\Models\Order\Seller\SellerOrder;
use App\Models\OrderAsset;

class OrderCancelAction{

    protected $sellerOrderCancel;

    public function __construct(SellerOrderCancel $sellerOrderCancel)
    {
        $this->sellerOrderCancel=$sellerOrderCancel;
    }
    public function cancelOrder(Request $request)
    {
        if($request->type=='reject')
        {
            $this->rejectRequest();
        }
        else
        {
            $this->sellerOrderCancel->update(
    [
                    'cancel_status'=>SellerOrderCancelStatus::APPROVED
                ]
            );
            $productSellerOrder=ProductSellerOrder::where('id',$this->sellerOrderCancel->product_seller_order_id)->where('seller_order_id',$this->sellerOrderCancel->seller_order_id)->where('product_id',$this->sellerOrderCancel->product_id)->first();
            $sellerOrder=SellerOrder::where('id',$productSellerOrder->seller_order_id)->first();
            $orderAsset=OrderAsset::where('order_id',$sellerOrder->order_id)->where('product_id',$productSellerOrder->product_id)->first();
            $productSellerOrder->update([
                'cancel_status'=>'1'
            ]);

            $orderAsset->update([
                'cancel_status'=>'1'
            ]);
        }
        
    }

    public function rejectRequest()
    {
        $this->sellerOrderCancel->update(
[
                'cancel_status'=>SellerOrderCancelStatus::REJECTED
            ]
        );
    }
}