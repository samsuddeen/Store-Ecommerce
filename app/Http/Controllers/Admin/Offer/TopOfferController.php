<?php

namespace App\Http\Controllers\Admin\Offer;

use App\Actions\Offer\TopOfferAction;
use App\Models\Admin\Offer\TopOffer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class TopOfferController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("admin.top-offer.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $topOffer = new TopOffer();
        return view("admin.top-offer.form",compact("topOffer"));
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
           (new TopOfferAction($request))->store();
            session()->flash('success',"new TopOffer created successfully");
            DB::commit();
            return redirect()->route('top-offer.index');
        } catch (\Throwable $th) {
            if ($th instanceof \Illuminate\Validation\ValidationException) {
                return redirect()->back()->withErrors($th->validator->errors())->withInput();
            }
            session()->flash('error',$th->getMessage());
            DB::rollback();
            return redirect()->back()->withInput();

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Admin\Offer\TopOffer  $topOffer
     * @return \Illuminate\Http\Response
     */
    public function show(TopOffer $topOffer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Admin\Offer\TopOffer  $topOffer
     * @return \Illuminate\Http\Response
     */
    public function edit(TopOffer $topOffer)
    {
        return view("admin.top-offer.form",compact("topOffer"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Admin\Offer\TopOffer  $topOffer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TopOffer $topOffer)
    {
         DB::beginTransaction();
          try{
            (new TopOfferAction($request))->update($topOffer);
            session()->flash('success',"new TopOffer created successfully");
            DB::commit();
            return redirect()->route('top-offer.index');

        } catch (\Throwable $th) {
            session()->flash('error',$th->getMessage());
            DB::rollback();
            return redirect()->back()->withInput();

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Admin\Offer\TopOffer  $topOffer
     * @return \Illuminate\Http\Response
     */
    public function destroy(TopOffer $topOffer)
    {
        try{
             $topOffer->delete();
              session()->flash('success',"TopOffer deleted successfully");
            return redirect()->route('top-offer.index');
        } catch (\Throwable $th) {
               session()->flash('error',$th->getMessage());
            return redirect()->back()->withInput();
        }
    }
}
    