<?php

namespace App\Http\Controllers\Admin;

use App\Models\ProductTag;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class ProductTagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $productTag = ProductTag::paginate(20);
        return view("admin.ProductTag.index",compact("productTag"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         $productTag = new ProductTag();
        return view("admin.ProductTag.form",compact("productTag"));
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
            ProductTag::create($request->all());
            session()->flash('success',"new ProductTag created successfully");
            DB::commit();
            return redirect()->route('ProductTag.index');
        } catch (\Throwable $th) {
            session()->flash('error',$th->getMessage());
            DB::rollback();
            return redirect()->back()->withInput();

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProductTag  $productTag
     * @return \Illuminate\Http\Response
     */
    public function show(ProductTag $productTag)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProductTag  $productTag
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductTag $productTag)
    {
        return view("admin.ProductTag.form",compact("productTag"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProductTag  $productTag
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProductTag $productTag)
    {
         DB::beginTransaction();
          try{
           $productTag->update($request->all());
            session()->flash('success',"new ProductTag created successfully");
            DB::commit();
            return redirect()->route('ProductTag.index');

        } catch (\Throwable $th) {
            session()->flash('error',$th->getMessage());
            DB::rollback();
            return redirect()->back()->withInput();

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProductTag  $productTag
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductTag $productTag)
    {
        try{
             $productTag->delete();
              session()->flash('success',"ProductTag deleted successfully");
            return redirect()->route('ProductTag.index');
        } catch (\Throwable $th) {
               session()->flash('error',$th->getMessage());
            return redirect()->back()->withInput();
        }
    }
}
