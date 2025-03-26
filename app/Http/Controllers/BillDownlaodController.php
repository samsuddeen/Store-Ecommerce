<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Seller;
use App\Models\Setting;
use App\Models\New_Customer;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use App\Models\Order\Seller\SellerOrder;
use App\Models\Order\Seller\SellerOrderStatus;

class BillDownlaodController extends Controller
{
    public function downloadBill(Request $request,$orderId)
    {
        $order = Order::where('ref_id', $orderId)->first();
        $customer=New_Customer::findOrFail($order->user_id);
        $refId = $order->ref_id;
        $data = $order->orderAssets;
        $data=collect($data)->filter(function($item)
        {
            if($item->cancel_status =='0')
            {
                return $item;
            }
        });
        $path = public_path('/backend/dist/img/logo.png');
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $img = file_get_contents($path);
        $tick = 'data:image/' . $type . ';base64,' . base64_encode($img);
        $pdf = PDF::setOptions(['defaultFont' => 'sans-serif'])->loadView('admin.billpdf', compact('order','data', 'refId','customer','tick'));
        return $pdf->download('Bill.pdf');
    }

    public function downloadSellerBill(Request $request,$id,$orderId)
    {
        
        $seller_order=SellerOrder::findOrFail($id);
        $order = Order::where('ref_id', $orderId)->first();
        $customer=New_Customer::findOrFail($order->user_id);
        $order=Order::findOrFail($seller_order->order_id);
        $data=$seller_order->sellerProductOrder;
        $refId = $order->ref_id;
        $seller_details=Seller::findOrFail($seller_order->seller_id);
        $path = public_path('/backend/dist/img/logo.png');
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $img = file_get_contents($path);
        $tick = 'data:image/' . $type . ';base64,' . base64_encode($img);
        $pdf = PDF::setOptions(['defaultFont' => 'sans-serif'])->loadView('admin.billpdfseller', compact('order','data', 'refId','customer','tick','seller_order'));
        return $pdf->download('Bill.pdf');
    }
}
