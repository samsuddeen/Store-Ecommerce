<?php

namespace App\Http\Controllers;

use App\Models\Customer\CustomerStatus;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class CustomerStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customerStatus = CustomerStatus::paginate(20);
        return view("admin.CustomerStatus.index",compact("customerStatus"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         $customerStatus = new CustomerStatus();
        return view("admin.CustomerStatus.form",compact("customerStatus"));
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
            CustomerStatus::create($request->all());
            request()->session()->flash('success',"new CustomerStatus created successfully");
            DB::commit();
            return redirect()->route('CustomerStatus.index');
        } catch (\Throwable $th) {
            request()->session()->flash('error',$th->getMessage());
            DB::rollback();
            return redirect()->back()->withInput();

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Customer\CustomerStatus  $customerStatus
     * @return \Illuminate\Http\Response
     */
    public function show(CustomerStatus $customerStatus)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Customer\CustomerStatus  $customerStatus
     * @return \Illuminate\Http\Response
     */
    public function edit(CustomerStatus $customerStatus)
    {
        return view("admin.CustomerStatus.form",compact("customerStatus"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer\CustomerStatus  $customerStatus
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CustomerStatus $customerStatus)
    {
         DB::beginTransaction();
          try{
           $customerStatus->update($request->all());
            request()->session()->flash('success',"new CustomerStatus created successfully");
            DB::commit();
            return redirect()->route('CustomerStatus.index');

        } catch (\Throwable $th) {
            request()->session()->flash('error',$th->getMessage());
            DB::rollback();
            return redirect()->back()->withInput();

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Customer\CustomerStatus  $customerStatus
     * @return \Illuminate\Http\Response
     */
    public function destroy(CustomerStatus $customerStatus)
    {
        try{
             $customerStatus->delete();
              request()->session()->flash('success',"CustomerStatus deleted successfully");
            return redirect()->route('CustomerStatus.index');
        } catch (\Throwable $th) {
               request()->session()->flash('error',$th->getMessage());
            return redirect()->back()->withInput();
        }
    }
}
