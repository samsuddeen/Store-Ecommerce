<?php

namespace App\Http\Controllers\Admin\Refund;

use App\Models\DirectRefund;
use Illuminate\Http\Request;
use App\Models\Refund\Refund;
use App\Data\Filter\FilterData;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Data\Payment\PaymentMethodData;
use App\Actions\Order\RefundStatusAction;
use Illuminate\Support\Facades\Validator;
use App\Actions\DirectRefund\DirectRefundStore;

class RefundController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data['payment_methods'] = (new PaymentMethodData())->getData();
        $filters = array_merge([

        ], $request->all());
        $data['filters'] = $filters;

        return view("admin.refund.index", $data);
    }

    public function refundpaidRequest(Request $request)
    {
        $data['payment_methods'] = (new PaymentMethodData())->getData();
        $filters = array_merge([

        ], $request->all());
        $data['filters'] = $filters;

        return view("admin.refund.paidrefund", $data);
    }



    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Refund\Refund  $refund
     * @return \Illuminate\Http\Response
     */
    public function show(Refund $refund)
    {
        
    }
    public function changeStatus(Request $request)
    {
       
        $validator = Validator::make($request->all(), [
            'refund_id'=>'required|exists:refunds,id',
            'type'=>'required',
            'remarks'=>Rule::requiredIf(fn () => $request->type == "REJECTED"),
        ]);
        if($validator->fails()){
            return back()->with('error', json_encode($validator->errors()))->withInput()->withErrors($validator->errors());
        }
        DB::beginTransaction();
        try {
            $refund = Refund::findOrFail($request->refund_id);
            (new RefundStatusAction($request, $refund, $request->type))->updateStatus();
            DB::commit();
            session()->flash('success', 'Successfully Status has been changed');
            return redirect()->route('refund.index');
        } catch (\Throwable $th) {
            DB::rollBack();
            session()->flash('error', 'Something is wrong');
            return back()->withInput();
        }
    }
        
    public function changeRefundDirectStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'refund_id'=>'required|exists:direct_refunds,id',
            'type'=>'required',
            'remarks'=>Rule::requiredIf(fn () => $request->type == "REJECTED"),
        ]);
        if($validator->fails()){
            return back()->with('error', json_encode($validator->errors()))->withInput()->withErrors($validator->errors());
        }
        DB::beginTransaction();
        try {
            $refund = DirectRefund::findOrFail($request->refund_id);
            (new DirectRefundStore($request, $refund, $request->type))->updateDirectRefundStatus();
            DB::commit();
            session()->flash('success', 'Successfully Status has been changed');
            return redirect()->route('refund.direct.index');
        } catch (\Throwable $th) {
            DB::rollBack();
            session()->flash('error', 'Something is wrong');
            return back()->withInput();
        }
    }

}
