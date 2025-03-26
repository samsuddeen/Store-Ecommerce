<?php

namespace App\Http\Controllers\Admin\Returnable;

use App\Actions\Order\ReturnOrderStatusAction;
use App\Data\Filter\FilterData;
use App\Http\Controllers\Controller;
use App\Models\Customer\ReturnOrder;
use App\Models\DirectRefund;
use App\Models\Order;
use App\Models\OrderAsset;
use App\Models\Product;
use App\Models\Refund\Refund;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AdminReturnAbleController extends Controller
{
    function __construct()
    {
    }
    public function index(Request $request)
    {
        $filters = (new FilterData($request))->getData();
        $data['filters']  = $filters;
        return view('admin.returned.index', $data);
    }
    public function changeStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'return_id' => 'required|exists:return_orders,id',
            'type' => 'required',
            'remarks' => Rule::requiredIf(fn () => $request->type == "REJECTED"),
        ]);
        if ($validator->fails()) {
            return back()->with('error', json_encode($validator->errors()))->withInput()->withErrors($validator->errors());
        }
        $returnOrder = ReturnOrder::findOrFail($request->return_id);
        DB::beginTransaction();
        try {
            $returnOrder->update([
                'is_new' => false,
            ]);
            (new ReturnOrderStatusAction($request, $returnOrder, $request->type))->updateStatus();
            DB::commit();
            session()->flash('success', 'Successfully Status has been changed');
            return redirect()->route('returnable.index');
        } catch (\Throwable $th) {
            DB::rollBack();
            session()->flash('error', 'Something is wrong');
            return back()->withInput();
        }
    }

    public function show($id)
    {
        $returnOrder = ReturnOrder::where('id', $id)->first();
        $orderAsset=OrderAsset::where('id',$returnOrder->order_asset_id)->first();
        $order=Order::where('id',$orderAsset->order_id)->first();
        $product_info = Product::where('id', $returnOrder->product_id)->first();
        // $returning_from = 
        return view('admin.returned.view', compact('returnOrder', 'product_info','order'));
    }

    

    public function refunddireactShow($id)
    {
        
        $returnOrder = Refund::where('id', $id)->first();
        return view('admin.returned.viewrefunddirect', compact('returnOrder'));
    }
}
