<?php

namespace App\Http\Controllers;

use App\Models\Order\Seller\SellerOrderStatus;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class SellerOrderStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sellerOrderStatus = SellerOrderStatus::paginate(20);
        return view("admin.SellerOrderStatus.index",compact("sellerOrderStatus"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         $sellerOrderStatus = new SellerOrderStatus();
        return view("admin.SellerOrderStatus.form",compact("sellerOrderStatus"));
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
            SellerOrderStatus::create($request->all());
            request()->session()->flash('success',"new SellerOrderStatus created successfully");
            DB::commit();
            return redirect()->route('SellerOrderStatus.index');
        } catch (\Throwable $th) {
            request()->session()->flash('error',$th->getMessage());
            DB::rollback();
            return redirect()->back()->withInput();

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order\Seller\SellerOrderStatus  $sellerOrderStatus
     * @return \Illuminate\Http\Response
     */
    public function show(SellerOrderStatus $sellerOrderStatus)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order\Seller\SellerOrderStatus  $sellerOrderStatus
     * @return \Illuminate\Http\Response
     */
    public function edit(SellerOrderStatus $sellerOrderStatus)
    {
        return view("admin.SellerOrderStatus.form",compact("sellerOrderStatus"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order\Seller\SellerOrderStatus  $sellerOrderStatus
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SellerOrderStatus $sellerOrderStatus)
    {
         DB::beginTransaction();
          try{
           $sellerOrderStatus->update($request->all());
            request()->session()->flash('success',"new SellerOrderStatus created successfully");
            DB::commit();
            return redirect()->route('SellerOrderStatus.index');

        } catch (\Throwable $th) {
            request()->session()->flash('error',$th->getMessage());
            DB::rollback();
            return redirect()->back()->withInput();

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order\Seller\SellerOrderStatus  $sellerOrderStatus
     * @return \Illuminate\Http\Response
     */
    public function destroy(SellerOrderStatus $sellerOrderStatus)
    {
        try{
             $sellerOrderStatus->delete();
              request()->session()->flash('success',"SellerOrderStatus deleted successfully");
            return redirect()->route('SellerOrderStatus.index');
        } catch (\Throwable $th) {
               request()->session()->flash('error',$th->getMessage());
            return redirect()->back()->withInput();
        }
    }
}
