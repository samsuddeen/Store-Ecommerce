<?php

namespace App\Http\Controllers;

use App\Models\ProductDangerous;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class ProductDangerousController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $productDangerous = ProductDangerous::paginate(20);
        return view("admin.ProductDangerous.index",compact("productDangerous"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         $productDangerous = new ProductDangerous();
        return view("admin.ProductDangerous.form",compact("productDangerous"));
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
            ProductDangerous::create($request->all());
            request()->session()->flash('success',"new ProductDangerous created successfully");
            DB::commit();
            return redirect()->route('ProductDangerous.index');
        } catch (\Throwable $th) {
            request()->session()->flash('error',$th->getMessage());
            DB::rollback();
            return redirect()->back()->withInput();

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProductDangerous  $productDangerous
     * @return \Illuminate\Http\Response
     */
    public function show(ProductDangerous $productDangerous)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProductDangerous  $productDangerous
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductDangerous $productDangerous)
    {
        return view("admin.ProductDangerous.form",compact("productDangerous"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProductDangerous  $productDangerous
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProductDangerous $productDangerous)
    {
         DB::beginTransaction();
          try{
           $productDangerous->update($request->all());
            request()->session()->flash('success',"new ProductDangerous created successfully");
            DB::commit();
            return redirect()->route('ProductDangerous.index');

        } catch (\Throwable $th) {
            request()->session()->flash('error',$th->getMessage());
            DB::rollback();
            return redirect()->back()->withInput();

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProductDangerous  $productDangerous
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductDangerous $productDangerous)
    {
        try{
             $productDangerous->delete();
              request()->session()->flash('success',"ProductDangerous deleted successfully");
            return redirect()->route('ProductDangerous.index');
        } catch (\Throwable $th) {
               request()->session()->flash('error',$th->getMessage());
            return redirect()->back()->withInput();
        }
    }
}
