<?php
namespace App\Http\Controllers\Admin\Payment;

use App\Actions\Payment\PaymentMethodAction;
use App\Enum\Payment\PaymentTypeEnum;
use App\Models\Payment\PaymentMethod;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class PaymentMethodController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("admin.payment-method.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['paymentMethod'] = new PaymentMethod();
        $data['types'] = (new PaymentTypeEnum())->getAllValues();
        return view("admin.payment-method.form", $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            "title" => "required"
        ]);
        DB::beginTransaction();
        try{
           (new PaymentMethodAction($request))->store();
            session()->flash('success',"new PaymentMethod created successfully");
            DB::commit();
            return redirect()->route('payment-method.index');
        } catch (\Throwable $th) {
            session()->flash('error',$th->getMessage());
            DB::rollback();
            return redirect()->back()->withInput();
        }
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Payment\PaymentMethod  $paymentMethod
     * @return \Illuminate\Http\Response
     */
    public function edit(PaymentMethod $paymentMethod)
    {
        $data['paymentMethod'] = $paymentMethod;
        $data['types'] = (new PaymentTypeEnum())->getAllValues();
        return view("admin.payment-method.form",$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Payment\PaymentMethod  $paymentMethod
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PaymentMethod $paymentMethod)
    {
         DB::beginTransaction();
          try{
            (new PaymentMethodAction($request))->update($paymentMethod);
            session()->flash('success',"Payment Method updated successfully");
            DB::commit();
            return redirect()->route('payment-method.index');

        } catch (\Throwable $th) {
            session()->flash('error',$th->getMessage());
            DB::rollback();
            return redirect()->back()->withInput();

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Payment\PaymentMethod  $paymentMethod
     * @return \Illuminate\Http\Response
     */
    public function destroy(PaymentMethod $paymentMethod)
    {
        if($paymentMethod->is_default == true){
            session()->flash('error',"Sorry You could not delete this is default payment method");
            return redirect()->route('payment-method.index');
        }
        try{
            $paymentMethod->delete();
            session()->flash('success',"PaymentMethod deleted successfully");
            return redirect()->route('payment-method.index');
        } catch (\Throwable $th) {
               session()->flash('error',$th->getMessage());
            return redirect()->back()->withInput();
        }
    }
    public function changeStatus(Request $request)
    {
        DB::beginTransaction();
        try {
            $payment = PaymentMethod::findOrFail($request->payment_id);
            if($payment->is_default == true && $request->status == 0){
                session()->flash('error', 'Sorry You could not inactive the default payment');
                return back()->withInput();
            }
            $payment->update([
                'status'=>$request->status,
            ]);
            DB::commit();
            session()->flash('success', 'Successfully Status changed');
            return redirect()->route('payment-method.index');
        } catch (\Throwable $th) {
            DB::rollBack();
            info($th->getMessage());
            session()->flash('error', 'Somethinf is wrong');
            return back()->withInput();
        }
    }
}