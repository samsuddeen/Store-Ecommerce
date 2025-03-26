<?php

namespace App\Http\Controllers\Admin\Returnable;

use App\Models\Admin\Returned\ReturnedStatus;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class ReturnedStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $returnedStatus = ReturnedStatus::paginate(20);
        return view("admin.ReturnedStatus.index",compact("returnedStatus"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         $returnedStatus = new ReturnedStatus();
        return view("admin.ReturnedStatus.form",compact("returnedStatus"));
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
            ReturnedStatus::create($request->all());
            request()->session()->flash('success',"new ReturnedStatus created successfully");
            DB::commit();
            return redirect()->route('ReturnedStatus.index');
        } catch (\Throwable $th) {
            request()->session()->flash('error',$th->getMessage());
            DB::rollback();
            return redirect()->back()->withInput();

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Admin\Returned\ReturnedStatus  $returnedStatus
     * @return \Illuminate\Http\Response
     */
    public function show(ReturnedStatus $returnedStatus)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Admin\Returned\ReturnedStatus  $returnedStatus
     * @return \Illuminate\Http\Response
     */
    public function edit(ReturnedStatus $returnedStatus)
    {
        return view("admin.ReturnedStatus.form",compact("returnedStatus"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Admin\Returned\ReturnedStatus  $returnedStatus
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ReturnedStatus $returnedStatus)
    {
         DB::beginTransaction();
          try{
           $returnedStatus->update($request->all());
            request()->session()->flash('success',"new ReturnedStatus created successfully");
            DB::commit();
            return redirect()->route('ReturnedStatus.index');

        } catch (\Throwable $th) {
            request()->session()->flash('error',$th->getMessage());
            DB::rollback();
            return redirect()->back()->withInput();

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Admin\Returned\ReturnedStatus  $returnedStatus
     * @return \Illuminate\Http\Response
     */
    public function destroy(ReturnedStatus $returnedStatus)
    {
        try{
             $returnedStatus->delete();
              request()->session()->flash('success',"ReturnedStatus deleted successfully");
            return redirect()->route('ReturnedStatus.index');
        } catch (\Throwable $th) {
               request()->session()->flash('error',$th->getMessage());
            return redirect()->back()->withInput();
        }
    }
}
