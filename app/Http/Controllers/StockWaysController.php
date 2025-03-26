<?php

namespace App\Http\Controllers;

use App\Models\StockWays;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class StockWaysController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $stockWays = StockWays::paginate(20);
        return view("admin.StockWays.index",compact("stockWays"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         $stockWays = new StockWays();
        return view("admin.StockWays.form",compact("stockWays"));
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
            StockWays::create($request->all());
            request()->session()->flash('success',"new StockWays created successfully");
            DB::commit();
            return redirect()->route('StockWays.index');
        } catch (\Throwable $th) {
            request()->session()->flash('error',$th->getMessage());
            DB::rollback();
            return redirect()->back()->withInput();

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\StockWays  $stockWays
     * @return \Illuminate\Http\Response
     */
    public function show(StockWays $stockWays)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\StockWays  $stockWays
     * @return \Illuminate\Http\Response
     */
    public function edit(StockWays $stockWays)
    {
        return view("admin.StockWays.form",compact("stockWays"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\StockWays  $stockWays
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StockWays $stockWays)
    {
         DB::beginTransaction();
          try{
           $stockWays->update($request->all());
            request()->session()->flash('success',"new StockWays created successfully");
            DB::commit();
            return redirect()->route('StockWays.index');

        } catch (\Throwable $th) {
            request()->session()->flash('error',$th->getMessage());
            DB::rollback();
            return redirect()->back()->withInput();

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\StockWays  $stockWays
     * @return \Illuminate\Http\Response
     */
    public function destroy(StockWays $stockWays)
    {
        try{
             $stockWays->delete();
              request()->session()->flash('success',"StockWays deleted successfully");
            return redirect()->route('StockWays.index');
        } catch (\Throwable $th) {
               request()->session()->flash('error',$th->getMessage());
            return redirect()->back()->withInput();
        }
    }
}
