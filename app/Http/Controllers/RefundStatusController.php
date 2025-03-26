<?php

namespace App\Http\Controllers;

use App\Models\Refund\RefundStatus;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class RefundStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $refundStatus = RefundStatus::paginate(20);
        return view("admin.RefundStatus.index",compact("refundStatus"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         $refundStatus = new RefundStatus();
        return view("admin.RefundStatus.form",compact("refundStatus"));
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
            RefundStatus::create($request->all());
            request()->session()->flash('success',"new RefundStatus created successfully");
            DB::commit();
            return redirect()->route('RefundStatus.index');
        } catch (\Throwable $th) {
            request()->session()->flash('error',$th->getMessage());
            DB::rollback();
            return redirect()->back()->withInput();

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Refund\RefundStatus  $refundStatus
     * @return \Illuminate\Http\Response
     */
    public function show(RefundStatus $refundStatus)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Refund\RefundStatus  $refundStatus
     * @return \Illuminate\Http\Response
     */
    public function edit(RefundStatus $refundStatus)
    {
        return view("admin.RefundStatus.form",compact("refundStatus"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Refund\RefundStatus  $refundStatus
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RefundStatus $refundStatus)
    {
         DB::beginTransaction();
          try{
           $refundStatus->update($request->all());
            request()->session()->flash('success',"new RefundStatus created successfully");
            DB::commit();
            return redirect()->route('RefundStatus.index');

        } catch (\Throwable $th) {
            request()->session()->flash('error',$th->getMessage());
            DB::rollback();
            return redirect()->back()->withInput();

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Refund\RefundStatus  $refundStatus
     * @return \Illuminate\Http\Response
     */
    public function destroy(RefundStatus $refundStatus)
    {
        try{
             $refundStatus->delete();
              request()->session()->flash('success',"RefundStatus deleted successfully");
            return redirect()->route('RefundStatus.index');
        } catch (\Throwable $th) {
               request()->session()->flash('error',$th->getMessage());
            return redirect()->back()->withInput();
        }
    }
}
