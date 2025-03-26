<?php

namespace App\Http\Controllers\Payout;

use App\Models\Payout\Payout;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class PayoutController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $payout = Payout::paginate(20);
        return view("admin.Payout.index",compact("payout"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         $payout = new Payout();
        return view("admin.Payout.form",compact("payout"));
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
            Payout::create($request->all());
            request()->session()->flash('success',"new Payout created successfully");
            DB::commit();
            return redirect()->route('Payout.index');
        } catch (\Throwable $th) {
            request()->session()->flash('error',$th->getMessage());
            DB::rollback();
            return redirect()->back()->withInput();

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Payout\Payout  $payout
     * @return \Illuminate\Http\Response
     */
    public function show(Payout $payout)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Payout\Payout  $payout
     * @return \Illuminate\Http\Response
     */
    public function edit(Payout $payout)
    {
        return view("admin.Payout.form",compact("payout"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Payout\Payout  $payout
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Payout $payout)
    {
         DB::beginTransaction();
          try{
           $payout->update($request->all());
            request()->session()->flash('success',"new Payout created successfully");
            DB::commit();
            return redirect()->route('Payout.index');

        } catch (\Throwable $th) {
            request()->session()->flash('error',$th->getMessage());
            DB::rollback();
            return redirect()->back()->withInput();

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Payout\Payout  $payout
     * @return \Illuminate\Http\Response
     */
    public function destroy(Payout $payout)
    {
        try{
             $payout->delete();
              request()->session()->flash('success',"Payout deleted successfully");
            return redirect()->route('Payout.index');
        } catch (\Throwable $th) {
               request()->session()->flash('error',$th->getMessage());
            return redirect()->back()->withInput();
        }
    }
}
