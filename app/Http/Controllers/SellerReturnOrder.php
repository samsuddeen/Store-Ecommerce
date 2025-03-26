<?php

namespace App\Http\Controllers;

use App\Helpers\Utilities;
use App\Models\OrderAsset;
use Illuminate\Http\Request;
use App\Data\Filter\FilterData;
use App\Http\Controllers\Controller;
use App\Models\Customer\ReturnOrder;
use Yajra\DataTables\Facades\DataTables;

class SellerReturnOrder extends Controller
{
    public function getSellerReturnOrder(Request $request)
    {
        
        if ($request->ajax()) {
            $seller=auth()->guard('seller')->user();
            $sellerProduct=ReturnOrder::orderBy('created_at','DESC')->get();
            $sellerProduct=collect($sellerProduct)->map(function($item) use ($seller)
            {
                
                if($item->getproduct !=null && $item->getproduct->seller_id==$seller->id)
                {
                    return $item;
                }
            });
        $sellerOrder=collect($sellerProduct)->whereNotNull();
            return DataTables::of($sellerOrder)
                ->addIndexColumn()
                ->addColumn('customer', function ($row) {
                    return $row->owner->name ?? "Not Defined";
                })
                ->addColumn('product', function ($row) {
                    return $row->getproduct->name ?? "Not Defined";
                })
                ->addColumn('price', function ($row) {
                    return formattedNepaliNumber($row->amount) ?? "Not Defined";
                })
                ->addColumn('qty', function ($row) {
                    return $row->qty ?? "Not Defined";
                })
                ->addColumn('total', function ($row) {
                    return formattedNepaliNumber($row->amount) ?? "Not Defined";
                })
                ->addColumn('action', function ($row) {
                    $show='';
                    $show.=  Utilities::button(href: route('view-return-order', [$row->getOrderAsset->order_id ?? 0,$row->id]), icon: "eye", color: "info", title: 'Show Order');
                    $show.="</div>";
                    return  $show;
                })
                ->rawColumns([ 'customer', 'product', 'price', 'qty', 'total', 'action'])
                ->make(true);
        }

        // //  LogEvent::dispatch('Order List View', 'Order List View', route('seller-order-index'));
       
        $filters = (new FilterData($request))->getData();
        $data['filters']  = $filters;
        
        return view("admin.seller.returnorder.index",$data);
    }

    public function viewReturnOrder(Request $request,$orderId,$returnId)
    {
        $data['sellerOrder']=OrderAsset::where('id',$orderId)->get();
        $data['return']=ReturnOrder::where('id',$returnId)->first();
        
        
        return view("seller.sellerreturnorder.view",$data);
    }
}
