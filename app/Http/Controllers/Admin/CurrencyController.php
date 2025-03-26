<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Currency;
use Illuminate\Http\Request;
use App\Models\CurrencyExchange;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;


class CurrencyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $currency = Currency::paginate(20);
        return view("admin.Currency.index",compact("currency"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         $currency = new Currency();
        return view("admin.Currency.form",compact("currency"));
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
            Currency::create($request->all());
            session()->flash('success',"new Currency created successfully");
            DB::commit();
            return redirect()->route('Currency.index');
        } catch (\Throwable $th) {
            session()->flash('error',$th->getMessage());
            DB::rollback();
            return redirect()->back()->withInput();

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Currency  $currency
     * @return \Illuminate\Http\Response
     */
    public function show(Currency $currency)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Currency  $currency
     * @return \Illuminate\Http\Response
     */
    public function edit(Currency $currency)
    {
        return view("admin.Currency.form",compact("currency"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Currency  $currency
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Currency $currency)
    {
         DB::beginTransaction();
          try{
           $currency->update($request->all());
            session()->flash('success',"new Currency created successfully");
            DB::commit();
            return redirect()->route('Currency.index');

        } catch (\Throwable $th) {
            session()->flash('error',$th->getMessage());
            DB::rollback();
            return redirect()->back()->withInput();

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Currency  $currency
     * @return \Illuminate\Http\Response
     */
    public function destroy(Currency $currency)
    {
        try{
             $currency->delete();
              session()->flash('success',"Currency deleted successfully");
            return redirect()->route('Currency.index');
        } catch (\Throwable $th) {
               session()->flash('error',$th->getMessage());
            return redirect()->back()->withInput();
        }
    }
}
