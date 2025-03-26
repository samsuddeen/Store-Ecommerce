<?php

namespace App\Http\Controllers;

use App\Models\CustomerCare;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class CustomerCareController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customerCare = CustomerCare::paginate(20);
        return view("admin.CustomerCare.index",compact("customerCare"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         $customerCare = new CustomerCare();
        return view("admin.CustomerCare.form",compact("customerCare"));
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
            CustomerCare::create($request->all());
            request()->session()->flash('success',"new CustomerCare created successfully");
            DB::commit();
            return redirect()->route('CustomerCare.index');
        } catch (\Throwable $th) {
            request()->session()->flash('error',$th->getMessage());
            DB::rollback();
            return redirect()->back()->withInput();

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CustomerCare  $customerCare
     * @return \Illuminate\Http\Response
     */
    public function show(CustomerCare $customerCare)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CustomerCare  $customerCare
     * @return \Illuminate\Http\Response
     */
    public function edit(CustomerCare $customerCare)
    {
        return view("admin.CustomerCare.form",compact("customerCare"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CustomerCare  $customerCare
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CustomerCare $customerCare)
    {
         DB::beginTransaction();
          try{
           $customerCare->update($request->all());
            request()->session()->flash('success',"new CustomerCare created successfully");
            DB::commit();
            return redirect()->route('CustomerCare.index');

        } catch (\Throwable $th) {
            request()->session()->flash('error',$th->getMessage());
            DB::rollback();
            return redirect()->back()->withInput();

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CustomerCare  $customerCare
     * @return \Illuminate\Http\Response
     */
    public function destroy(CustomerCare $customerCare)
    {
        try{
             $customerCare->delete();
              request()->session()->flash('success',"CustomerCare deleted successfully");
            return redirect()->route('CustomerCare.index');
        } catch (\Throwable $th) {
               request()->session()->flash('error',$th->getMessage());
            return redirect()->back()->withInput();
        }
    }
}
