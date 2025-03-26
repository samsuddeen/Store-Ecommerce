<?php

namespace App\Http\Controllers;

use App\Models\Seller\SellerDocument;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class SellerDocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sellerDocument = SellerDocument::paginate(20);
        return view("admin.SellerDocument.index",compact("sellerDocument"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         $sellerDocument = new SellerDocument();
        return view("admin.SellerDocument.form",compact("sellerDocument"));
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
            SellerDocument::create($request->all());
            request()->session()->flash('success',"new SellerDocument created successfully");
            DB::commit();
            return redirect()->route('SellerDocument.index');
        } catch (\Throwable $th) {
            request()->session()->flash('error',$th->getMessage());
            DB::rollback();
            return redirect()->back()->withInput();

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Seller\SellerDocument  $sellerDocument
     * @return \Illuminate\Http\Response
     */
    public function show(SellerDocument $sellerDocument)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Seller\SellerDocument  $sellerDocument
     * @return \Illuminate\Http\Response
     */
    public function edit(SellerDocument $sellerDocument)
    {
        return view("admin.SellerDocument.form",compact("sellerDocument"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Seller\SellerDocument  $sellerDocument
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SellerDocument $sellerDocument)
    {
         DB::beginTransaction();
          try{
           $sellerDocument->update($request->all());
            request()->session()->flash('success',"new SellerDocument created successfully");
            DB::commit();
            return redirect()->route('SellerDocument.index');

        } catch (\Throwable $th) {
            request()->session()->flash('error',$th->getMessage());
            DB::rollback();
            return redirect()->back()->withInput();

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Seller\SellerDocument  $sellerDocument
     * @return \Illuminate\Http\Response
     */
    public function destroy(SellerDocument $sellerDocument)
    {
        try{
             $sellerDocument->delete();
              request()->session()->flash('success',"SellerDocument deleted successfully");
            return redirect()->route('SellerDocument.index');
        } catch (\Throwable $th) {
               request()->session()->flash('error',$th->getMessage());
            return redirect()->back()->withInput();
        }
    }
}
