<?php

namespace App\Http\Controllers;

use App\Models\ProductCancelReason;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class ProductCancelReasonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $productCancelReason = ProductCancelReason::paginate(20);
        return view("admin.ProductCancelReason.index",compact("productCancelReason"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         $productCancelReason = new ProductCancelReason();
        return view("admin.ProductCancelReason.form",compact("productCancelReason"));
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
            ProductCancelReason::create($request->all());
            request()->session()->flash('success',"new ProductCancelReason created successfully");
            DB::commit();
            return redirect()->route('ProductCancelReason.index');
        } catch (\Throwable $th) {
            request()->session()->flash('error',$th->getMessage());
            DB::rollback();
            return redirect()->back()->withInput();

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProductCancelReason  $productCancelReason
     * @return \Illuminate\Http\Response
     */
    public function show(ProductCancelReason $productCancelReason)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProductCancelReason  $productCancelReason
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductCancelReason $productCancelReason)
    {
        return view("admin.ProductCancelReason.form",compact("productCancelReason"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProductCancelReason  $productCancelReason
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProductCancelReason $productCancelReason)
    {
         DB::beginTransaction();
          try{
           $productCancelReason->update($request->all());
            request()->session()->flash('success',"new ProductCancelReason created successfully");
            DB::commit();
            return redirect()->route('ProductCancelReason.index');

        } catch (\Throwable $th) {
            request()->session()->flash('error',$th->getMessage());
            DB::rollback();
            return redirect()->back()->withInput();

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProductCancelReason  $productCancelReason
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductCancelReason $productCancelReason)
    {
        try{
             $productCancelReason->delete();
              request()->session()->flash('success',"ProductCancelReason deleted successfully");
            return redirect()->route('ProductCancelReason.index');
        } catch (\Throwable $th) {
               request()->session()->flash('error',$th->getMessage());
            return redirect()->back()->withInput();
        }
    }
}
