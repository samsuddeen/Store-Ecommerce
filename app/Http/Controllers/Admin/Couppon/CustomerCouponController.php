<?php

namespace App\Http\Controllers\Admin\Couppon;

use App\Data\Coupon\CouponData;
use App\Data\Coupon\CustomerCouponData;
use App\Data\Customer\CustomerData;
use App\Data\Filter\FilterData;
use App\Models\Admin\Coupon\Customer\CustomerCoupon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class CustomerCouponController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = [];
        $filters = (new FilterData($request));
        $coupons = (new CustomerCouponData($filters))->getData();
        $data['coupons'] = $coupons;
        return view("admin.coupon.customer-coupon.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $customerCoupon = new CustomerCoupon();
        $data = (new CustomerData())->getActiveCustomers();
        $data['customerCoupon'] = $customerCoupon;
        $data['coupons'] = (new CouponData())->getCoupons();
        return view("admin.coupon.customer-coupon.form", $data);
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
            $input = $request->all();
            if($request->customer_id=='allData')
            {
                $input['customer_id']=0;
            }
            $code = $request->code;
            if($code)
            {
                $input['code'] = $code;
            }else{
                $input['code'] = strtoupper(substr(md5(microtime(1) * rand(0, 9999)), 0, 8));
            }
            CustomerCoupon::create($input);
            session()->flash('success',"new Customer Coupon created successfully");
            DB::commit();
            return redirect()->route('customer-coupon.index');
        } catch (\Throwable $th) {
            session()->flash('error',$th->getMessage());
            DB::rollback();
            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Admin\Coupon\Customer\CustomerCoupon  $customerCoupon
     * @return \Illuminate\Http\Response
     */
    public function show(CustomerCoupon $customerCoupon)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Admin\Coupon\Customer\CustomerCoupon  $customerCoupon
     * @return \Illuminate\Http\Response
     */
    public function edit(CustomerCoupon $customerCoupon)
    {
        $data = (new CustomerData())->getActiveCustomers();
        $data['customerCoupon'] = $customerCoupon;
        $data['coupons'] = (new CouponData())->getCoupons();
        return view("admin.coupon.customer-coupon.form",$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Admin\Coupon\Customer\CustomerCoupon  $customerCoupon
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CustomerCoupon $customerCoupon)
    {
        
         DB::beginTransaction();
          try{
            $input = $request->all();
            if($request->customer_id=='allData')
            {
                $input['customer_id']=0;
            }
        //    $input['code'] = strtoupper(substr(md5(microtime(1) * rand(0, 9999)), 0, 8));
           $customerCoupon->update($input);
            session()->flash('success',"new Customer Coupon created successfully");
            DB::commit();
            return redirect()->route('customer-coupon.index');

        } catch (\Throwable $th) {
            session()->flash('error',$th->getMessage());
            DB::rollback();
            return redirect()->back()->withInput();

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Admin\Coupon\Customer\CustomerCoupon  $customerCoupon
     * @return \Illuminate\Http\Response
     */
    public function destroy(CustomerCoupon $customerCoupon)
    {
        try{
             $customerCoupon->delete();
              session()->flash('success',"Customer Coupon deleted successfully");
            return redirect()->back();
        } catch (\Throwable $th) {
               session()->flash('error',$th->getMessage());
            return redirect()->back()->withInput();
        }
    }
}
