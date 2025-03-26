<?php

namespace App\Http\Controllers;

use App\Models\CancellationReason;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class CancellationReasonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cancellationReason = CancellationReason::paginate(20);
        return view("admin.CancellationReason.index",compact("cancellationReason"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("admin.CancellationReason.form");
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
        $data = $request->all();
        try{
            $data['user_id'] = Auth::user()->id;
            CancellationReason::create($data);
            request()->session()->flash('success',"new CancellationReason created successfully");
            DB::commit();
            return redirect()->route('cancel-reason.index');
        } catch (\Throwable $th) {
            request()->session()->flash('error',$th->getMessage());
            DB::rollback();
            return redirect()->back()->withInput();

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CancellationReason  $cancellationReason
     * @return \Illuminate\Http\Response
     */
    public function show(CancellationReason $cancellationReason)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CancellationReason  $cancellationReason
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {   
        $cancellationReason = CancellationReason::find($id);
        return view("admin.CancellationReason.form",compact("cancellationReason"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CancellationReason  $cancellationReason
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {   
        $cancellationReason = CancellationReason::find($id);
         DB::beginTransaction();
          try{
           $cancellationReason->update($request->all());
            request()->session()->flash('success',"new CancellationReason created successfully");
            DB::commit();
            return redirect()->route('cancel-reason.index');

        } catch (\Throwable $th) {
            request()->session()->flash('error',$th->getMessage());
            DB::rollback();
            return redirect()->back()->withInput();

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CancellationReason  $cancellationReason
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {   
        $cancellationReason = CancellationReason::find($id);
        try{
             $cancellationReason->delete();
              request()->session()->flash('success',"CancellationReason deleted successfully");
            return redirect()->route('cancel-reason.index');
        } catch (\Throwable $th) {
               request()->session()->flash('error',$th->getMessage());
            return redirect()->back()->withInput();
        }
    }
}
