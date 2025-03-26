<?php

namespace App\Http\Controllers\Admin;

use App\Models\CurrencyExchange;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class CurrencyExchangeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $currencyExchange = CurrencyExchange::paginate(20);
        return view("admin.CurrencyExchange.index",compact("currencyExchange"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         $currencyExchange = new CurrencyExchange();
        return view("admin.CurrencyExchange.form",compact("currencyExchange"));
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
            CurrencyExchange::create($request->all());
            request()->session()->flash('success',"new CurrencyExchange created successfully");
            DB::commit();
            return redirect()->route('CurrencyExchange.index');
        } catch (\Throwable $th) {
            request()->session()->flash('error',$th->getMessage());
            DB::rollback();
            return redirect()->back()->withInput();

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CurrencyExchange  $currencyExchange
     * @return \Illuminate\Http\Response
     */
    public function show(CurrencyExchange $currencyExchange)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CurrencyExchange  $currencyExchange
     * @return \Illuminate\Http\Response
     */
    public function edit(CurrencyExchange $currencyExchange)
    {
        return view("admin.CurrencyExchange.form",compact("currencyExchange"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CurrencyExchange  $currencyExchange
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CurrencyExchange $currencyExchange)
    {
         DB::beginTransaction();
          try{
           $currencyExchange->update($request->all());
            request()->session()->flash('success',"new CurrencyExchange created successfully");
            DB::commit();
            return redirect()->route('CurrencyExchange.index');

        } catch (\Throwable $th) {
            request()->session()->flash('error',$th->getMessage());
            DB::rollback();
            return redirect()->back()->withInput();

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CurrencyExchange  $currencyExchange
     * @return \Illuminate\Http\Response
     */
    public function destroy(CurrencyExchange $currencyExchange)
    {
        try{
             $currencyExchange->delete();
              request()->session()->flash('success',"CurrencyExchange deleted successfully");
            return redirect()->route('CurrencyExchange.index');
        } catch (\Throwable $th) {
               request()->session()->flash('error',$th->getMessage());
            return redirect()->back()->withInput();
        }
    }
}
