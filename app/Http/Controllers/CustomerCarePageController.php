<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Data\CustomerCare\CustomerCareData;
use App\Actions\CustomerCare\CustomerCareAction;
use App\Models\CustomerCarePage;

class CustomerCarePageController extends Controller
{
    public function index()
    {
        $data=(new CustomerCareData())->getData();
        return view('admin.Customarecarepage.index',$data);
    }

    public function create()
    {
        return view('admin.Customarecarepage.form');
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try{
            (new CustomerCareAction($request))->store();
            DB::commit();
            $request->session()->flash('success','Content Added Successfully !!');
            return redirect()->route('customercarepage.index');
        }catch(\Throwable $th)
        {
            DB::rollback();
            $request->session()->flash('error','Something Went Wrong !!');
            return redirect()->back();
        }
    }

    public function edit(request $request,$customerpagecare)
    {
        $data['data']=(new CustomerCareData($customerpagecare))->formData();
        return view('admin.Customarecarepage.form',$data);
    }

    public function update(Request $request,$customerpagecare)
    {
        // dd('ok');
        DB::beginTransaction();
        try{
            (new CustomerCareAction($request))->update($customerpagecare);
            DB::commit();
            $request->session()->flash('success','Content Update Successfully !!');
            return redirect()->route('customercarepage.index');
        }catch(\Throwable $th)
        {
            DB::rollback();
            $request->session()->flash('error','Something Went Wrong !!');
            return redirect()->back();
        }
    }

    public function destroy(Request $request,$customerpagecare)
    {
        // dd('ok');
        DB::beginTransaction();
        try{
            (new CustomerCareAction($request))->destroy($customerpagecare);
            DB::commit();
            $request->session()->flash('success','Content Deleted Successfully !!');
            return redirect()->route('customercarepage.index');
        }catch(\Throwable $th)
        {
            DB::rollback();
            $request->session()->flash('error','Something Went Wrong !!');
            return redirect()->back();
        }
    }
}
