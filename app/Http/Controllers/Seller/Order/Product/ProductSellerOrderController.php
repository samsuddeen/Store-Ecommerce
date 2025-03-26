<?php

namespace App\Http\Controllers\Seller\Order\Product;

use App\Models\Order\Seller\ProductSellerOrder;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class ProductSellerOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $productSellerOrder = ProductSellerOrder::paginate(20);
        return view("admin.ProductSellerOrder.index",compact("productSellerOrder"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         $productSellerOrder = new ProductSellerOrder();
        return view("admin.ProductSellerOrder.form",compact("productSellerOrder"));
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
            ProductSellerOrder::create($request->all());
            request()->session()->flash('success',"new ProductSellerOrder created successfully");
            DB::commit();
            return redirect()->route('ProductSellerOrder.index');
        } catch (\Throwable $th) {
            request()->session()->flash('error',$th->getMessage());
            DB::rollback();
            return redirect()->back()->withInput();

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order\Seller\ProductSellerOrder  $productSellerOrder
     * @return \Illuminate\Http\Response
     */
    public function show(ProductSellerOrder $productSellerOrder)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order\Seller\ProductSellerOrder  $productSellerOrder
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductSellerOrder $productSellerOrder)
    {
        return view("admin.ProductSellerOrder.form",compact("productSellerOrder"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order\Seller\ProductSellerOrder  $productSellerOrder
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProductSellerOrder $productSellerOrder)
    {
         DB::beginTransaction();
          try{
           $productSellerOrder->update($request->all());
            request()->session()->flash('success',"new ProductSellerOrder created successfully");
            DB::commit();
            return redirect()->route('ProductSellerOrder.index');

        } catch (\Throwable $th) {
            request()->session()->flash('error',$th->getMessage());
            DB::rollback();
            return redirect()->back()->withInput();

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order\Seller\ProductSellerOrder  $productSellerOrder
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductSellerOrder $productSellerOrder)
    {
        try{
             $productSellerOrder->delete();
              request()->session()->flash('success',"ProductSellerOrder deleted successfully");
            return redirect()->route('ProductSellerOrder.index');
        } catch (\Throwable $th) {
               request()->session()->flash('error',$th->getMessage());
            return redirect()->back()->withInput();
        }
    }
}
