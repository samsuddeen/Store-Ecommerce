<?php

namespace App\Http\Controllers;

use App\Models\SMTP\SMTP;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class SMTPController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sMTP = SMTP::paginate(20);
        return view("admin.SMTP.index",compact("sMTP"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         $sMTP = new SMTP();
        return view("admin.SMTP.form",compact("sMTP"));
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
            SMTP::create($request->all());
            request()->session()->flash('success',"new SMTP created successfully");
            DB::commit();
            return redirect()->route('SMTP.index');
        } catch (\Throwable $th) {
            request()->session()->flash('error',$th->getMessage());
            DB::rollback();
            return redirect()->back()->withInput();

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SMTP\SMTP  $sMTP
     * @return \Illuminate\Http\Response
     */
    public function show(SMTP $sMTP)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SMTP\SMTP  $sMTP
     * @return \Illuminate\Http\Response
     */
    public function edit(SMTP $sMTP)
    {
        return view("admin.SMTP.form",compact("sMTP"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SMTP\SMTP  $sMTP
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SMTP $sMTP)
    {
         DB::beginTransaction();
          try{
           $sMTP->update($request->all());
            request()->session()->flash('success',"new SMTP created successfully");
            DB::commit();
            return redirect()->route('SMTP.index');

        } catch (\Throwable $th) {
            request()->session()->flash('error',$th->getMessage());
            DB::rollback();
            return redirect()->back()->withInput();

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SMTP\SMTP  $sMTP
     * @return \Illuminate\Http\Response
     */
    public function destroy(SMTP $sMTP)
    {
        try{
             $sMTP->delete();
              request()->session()->flash('success',"SMTP deleted successfully");
            return redirect()->route('SMTP.index');
        } catch (\Throwable $th) {
               request()->session()->flash('error',$th->getMessage());
            return redirect()->back()->withInput();
        }
    }
}
