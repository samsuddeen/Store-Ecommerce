<?php

namespace App\Http\Controllers\Admin\Seller;

use App\Actions\Seller\SellerAction;
use App\Data\Seller\SellerData;
use App\Http\Controllers\Controller;
use App\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SellerCotroller extends Controller
{
    public function index(Request $request)
    {
        $filters = array_merge([], $request->all());
        $data['sellers'] = (new SellerData($filters))->getData();
        return view('admin.seller.index', $data);
    }
    public function create(Request $request)
    {
        $data = [];
        return view('admin.seller.form', $data);
    }
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            (new SellerAction($request))->store();
            DB::commit();
            return redirect()->route('seller.index')->with('success', 'Successfully Selle has been created');
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', $th->getMessage());
        }
    }

    public function edit(Request $request, Seller $seller)
    {
        $data['seller'] = $seller;
        return view('admin.seller.form', $data);
    }

    public function update(Request $request, Seller $seller)
    {
        DB::beginTransaction();
        try {
            (new SellerAction($request))->update($seller);
            DB::commit();
            return redirect()->route('seller.index')->with('success', 'Seller Successfully Updated');
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->withInput()->with('error', $th->getMessage());
        }
    }
    public function show(Seller $seller)
    {
        $data['seller'] = $seller;
        return view('admin.seller.show', $data);
    }



    public function destroy(Seller $seller)
    {
        $seller->delete();
        return redirect()->route('seller.index')->with('success', 'Succesfully Deleted');
    }
}
