<?php

namespace App\Http\Controllers\Admin\SMTP;

use App\Actions\Setting\SMTP\SMTPAction;
use App\Models\SMTP\SMTP;
use App\Http\Controllers\Controller;
use App\Http\Requests\SMTPStoreRequest;
use App\Http\Requests\SMTPUpdateRequest;
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
        return view("admin.smtp.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         $smtp = new SMTP();
        return view("admin.smtp.form",compact("smtp"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SMTPStoreRequest $request)
    {
        DB::beginTransaction();
        try{
            (new SMTPAction($request))->store();
            session()->flash('success',"new SMTP created successfully");
            DB::commit();
            return redirect()->route('smtp.index');
        } catch (\Throwable $th) {
            session()->flash('error',$th->getMessage());
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
    public function edit(SMTP $smtp)
    {
        return view("admin.smtp.form",compact("smtp"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SMTP\SMTP  $sMTP
     * @return \Illuminate\Http\Response
     */
    public function update(SMTPUpdateRequest $request, SMTP $sMtp)
    {
        DB::beginTransaction();
          try{
            (new SMTPAction($request))->update($sMtp);
            session()->flash('success',"new SMTP created successfully");
            DB::commit();
            return redirect()->route('smtp.index');
        } catch (\Throwable $th) {
            session()->flash('error',$th->getMessage());
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
    public function destroy($id)
    {
        $sMTP = SMTP::findOrFail($id);
        if($sMTP->is_default == true){
            session()->flash('error',"Sorry This is the default SMTP");
            return back();
        }
        try{
            $sMTP->delete();
            session()->flash('success',"SMTP deleted successfully");
            return redirect()->route('SMTP.index');
        } catch (\Throwable $th) {
            session()->flash('error',$th->getMessage());
            return redirect()->back()->withInput();
        }
    }
}
