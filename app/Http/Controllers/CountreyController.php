<?php

namespace App\Http\Controllers;

use App\Models\Countrey;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\CountryStoreRequest;


class CountreyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $countries=Countrey::get();
        return view("admin.country.index",compact('countries'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $countries = new Countrey();
        return view("admin.country.form",compact("countries"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CountryStoreRequest $request)
    {
        DB::beginTransaction();
        try{
            $request['country_slug']=Str::slug($request->name);
            Countrey::create($request->all());
            request()->session()->flash('success',"New Countrey created successfully");
            DB::commit();
            return redirect()->route('countries.index');
        } catch (\Throwable $th) {
            request()->session()->flash('error',$th->getMessage());
            DB::rollback();
            return redirect()->back()->withInput();

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Countrey  $countrey
     * @return \Illuminate\Http\Response
     */
    public function show(Countrey $countrey)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Countrey  $countrey
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
        $countries = Countrey::findOrFail($id);
        return view("admin.country.form",compact("countries"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Countrey  $countrey
     * @return \Illuminate\Http\Response
     */
    public function update(CountryStoreRequest $request,$id)
    {
        $countrey=Countrey::findOrFail($id);
         DB::beginTransaction();
          try{
           $countrey->update($request->all());
            request()->session()->flash('success',"new Countrey created successfully");
            DB::commit();
            return redirect()->route('countries.index');

        } catch (\Throwable $th) {
            request()->session()->flash('error',$th->getMessage());
            DB::rollback();
            return redirect()->back()->withInput();

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Countrey  $countrey
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $countrey=Countrey::findOrFail($id);
        try{
             $countrey->delete();
              request()->session()->flash('success',"Countrey deleted successfully");
            return redirect()->route('countries.index');
        } catch (\Throwable $th) {
               request()->session()->flash('error',$th->getMessage());
            return redirect()->back()->withInput();
        }
    }
}
