<?php

namespace App\Http\Controllers\Seller;

use App\Actions\Seller\Document\SellerDocumentAction;
use App\Models\Seller\SellerDocument;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class SellerDocumentController extends Controller
{   
    public function store(Request $request)
    {
        DB::beginTransaction();
        try{
            (new SellerDocumentAction($request))->store();
            session()->flash('success',"New Seller Document created successfully");
            DB::commit();
            return redirect()->route('seller.index');
        } catch (\Throwable $th) {
            session()->flash('error',$th->getMessage());
            DB::rollback();
            return redirect()->back()->withInput();

        }
    }
    public function destroy(SellerDocument $sellerDocument)
    {
        try{
             $sellerDocument->delete();
              session()->flash('success',"SellerDocument deleted successfully");
            return redirect()->route('SellerDocument.index');
        } catch (\Throwable $th) {
               session()->flash('error',$th->getMessage());
            return redirect()->back()->withInput();
        }
    }
}
