<?php
namespace App\Actions\SellerOrderCancel;

use Illuminate\Http\Request;
use App\Models\SellerOrderCancel;

class SellerOrderCancelAction{

    protected $request;
    public function __construct(Request $request)
    {
        $this->request=$request;
    }

    public function cancelRequest()
    {
        $seller=auth()->guard('seller')->user()->id;
        $cancelRequest=SellerOrderCancel::create(
            [
                'seller_id'=>$seller,
                'order_id'=>$this->request->order_id,
                'reason'=>$this->request->remarks
            ]
        );
        
    }
}