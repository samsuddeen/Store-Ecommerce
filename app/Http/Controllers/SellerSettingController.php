<?php

namespace App\Http\Controllers;

use App\Models\Seller\SellerSetting;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class SellerSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sellerSetting = SellerSetting::where('seller_id', auth()->guard('seller')->user()->id)->first();
        if ($sellerSetting != null) {
            return view("seller.setting.form", compact("sellerSetting"));
        } else {
            $sellerSetting = new SellerSetting();
            return view("seller.setting.form", compact("sellerSetting"));
        }
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $sellerSetting = SellerSetting::where('seller_id', auth()->guard('seller')->user()->id)->first();
        if ($sellerSetting != null) {
            return view("seller.setting.form", compact("sellerSetting"));
        } else {
            $sellerSetting = new SellerSetting();
            return view("seller.setting.form", compact("sellerSetting"));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $input = $request->all();
        $input['seller_id'] = auth()->guard('seller')->user()->id;

        DB::beginTransaction();
        try {
            SellerSetting::create($input);
            session()->flash('success', "new SellerSetting created successfully");
            DB::commit();
            return redirect()->route('seller-setting.index');
        } catch (\Throwable $th) {
            session()->flash('error', $th->getMessage());
            DB::rollback();
            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Seller\SellerSetting  $sellerSetting
     * @return \Illuminate\Http\Response
     */
    public function show(SellerSetting $sellerSetting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Seller\SellerSetting  $sellerSetting
     * @return \Illuminate\Http\Response
     */
    public function edit(SellerSetting $sellerSetting)
    {
        $sellerSetting = SellerSetting::where('seller_id', auth()->guard('seller')->user()->id)->first();
        if ($sellerSetting != null) {
            return view("seller.setting.form", compact("sellerSetting"));
        } else {
            $sellerSetting = new SellerSetting();
            return view("seller.setting.form", compact("sellerSetting"));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Seller\SellerSetting  $sellerSetting
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SellerSetting $sellerSetting)
    {
        DB::beginTransaction();
        try {
            $sellerSetting->update($request->all());
            session()->flash('success', "new SellerSetting created successfully");
            DB::commit();
            return redirect()->route('seller-setting.index');
        } catch (\Throwable $th) {
            session()->flash('error', $th->getMessage());
            DB::rollback();
            return redirect()->back()->withInput();
        }
    }
}
