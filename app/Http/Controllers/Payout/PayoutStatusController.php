<?php

namespace App\Http\Controllers\Payout;

use App\Models\Payout\PayoutStatus;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class PayoutStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $payoutStatus = PayoutStatus::paginate(20);
        return view("admin.PayoutStatus.index",compact("payoutStatus"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         $payoutStatus = new PayoutStatus();
        return view("admin.PayoutStatus.form",compact("payoutStatus"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try{
            PayoutStatus::create($request->all());
            request()->session()->flash('success',"new PayoutStatus created successfully");
            DB::commit();
            return redirect()->route('PayoutStatus.index');
        } catch (\Throwable $th) {
            request()->session()->flash('error',$th->getMessage());
            DB::rollback();
            return redirect()->back()->withInput();

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Payout\PayoutStatus  $payoutStatus
     * @return \Illuminate\Http\Response
     */
    public function show(PayoutStatus $payoutStatus)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Payout\PayoutStatus  $payoutStatus
     * @return \Illuminate\Http\Response
     */
    public function edit(PayoutStatus $payoutStatus)
    {
        return view("admin.PayoutStatus.form",compact("payoutStatus"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Payout\PayoutStatus  $payoutStatus
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PayoutStatus $payoutStatus)
    {
         DB::beginTransaction();
          try{
           $payoutStatus->update($request->all());
            request()->session()->flash('success',"new PayoutStatus created successfully");
            DB::commit();
            return redirect()->route('PayoutStatus.index');

        } catch (\Throwable $th) {
            request()->session()->flash('error',$th->getMessage());
            DB::rollback();
            return redirect()->back()->withInput();

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Payout\PayoutStatus  $payoutStatus
     * @return \Illuminate\Http\Response
     */
    public function destroy(PayoutStatus $payoutStatus)
    {
        try{
             $payoutStatus->delete();
              request()->session()->flash('success',"PayoutStatus deleted successfully");
            return redirect()->route('PayoutStatus.index');
        } catch (\Throwable $th) {
               request()->session()->flash('error',$th->getMessage());
            return redirect()->back()->withInput();
        }
    }
}
