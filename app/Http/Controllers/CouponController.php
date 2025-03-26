<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\CouponRequest;
use App\Http\Requests\CouponUpdateRequest;


class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("admin.coupon.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         $coupon = new Coupon();
        return view("admin.coupon.form",compact("coupon"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CouponRequest $request)
    {
        // return $request;
        DB::beginTransaction();
        $input = $request->all();
        if($request->coupon_code)
        {
            $input['coupon_code'] = $request->coupon_code;
        }else{
            $input['coupon_code'] = strtoupper(substr(md5(microtime(1) * rand(0, 9999)), 0, 8));
        }

        if($request->check !== '%'){
            $input['is_percentage']='no';
            // THIS MUST BE CHANGED AFTER CURRENCY ADDON
            $input['currency_id']=1;
        }
        if($request->check == null){
            $input['is_percentage']='yes';
            // THIS MUST BE CHANGED AFTER CURRENCY ADDON
            $input['currency_id']=1;
        }
        $input['user_id']=$request->user()->id;
        $input['slug']=Str::slug($request->title);
        try{
            Coupon::create($input);
            session()->flash('success',"new Coupon created successfully");
            DB::commit();
            return redirect()->route('coupon.index');
        } catch (\Throwable $th) {  
            session()->flash('error',$th->getMessage());
            DB::rollback();
            return redirect()->back()->withInput();

        }
    }
    
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function show(Coupon $coupon)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function edit(Coupon $coupon)
    {
        return view("admin.coupon.form",compact("coupon"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function update(CouponUpdateRequest $request, Coupon $coupon)
    {
         DB::beginTransaction();
         $input = $request->all();
         if($request->check !== '%'){
            $input['is_percentage']='no';
            // THIS MUST BE CHANGED AFTER CURRENCY ADDON
            $input['currency_id']=1;
        }
        if($request->check == null){
            $input['is_percentage']='yes';
            // THIS MUST BE CHANGED AFTER CURRENCY ADDON
            $input['currency_id']=1;
        }
         $input['user_id']=$request->user()->id;
         $input['slug']=Str::slug($request->title);
        
        
        //  dd($input);
          try{
           $coupon->update($input);
        //    dd($coupon);
            session()->flash('success',"new Coupon created successfully");
            DB::commit();
            return redirect()->route('coupon.index');

        } catch (\Throwable $th) {
            session()->flash('error',$th->getMessage());
            DB::rollback();
            return redirect()->back()->withInput();

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function destroy(Coupon $coupon)
    {
        try{
             $coupon->delete();
              session()->flash('success',"Coupon deleted successfully");
            return redirect()->back();
        } catch (\Throwable $th) {
               session()->flash('error',$th->getMessage());
            return redirect()->back()->withInput();
        }
    }
}
