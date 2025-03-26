<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\RetailerOfferSection;
use App\Actions\Offer\RetailerOfferAction;


class RetailerOfferSectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("admin.retaileroffer.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $topOffer = new RetailerOfferSection();
        return view("admin.retaileroffer.form",compact("topOffer"));
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
           (new RetailerOfferAction($request))->store();
            session()->flash('success',"new Retailer Offer created successfully");
            DB::commit();
            return redirect()->route('retailer_offer.index');
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
    public function show(RetailerOfferSection $topOffer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Admin\Offer\TopOffer  $topOffer
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $topOffer=RetailerOfferSection::findOrFail($id);
        return view("admin.retaileroffer.form",compact("topOffer"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Admin\Offer\TopOffer  $topOffer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
         DB::beginTransaction();
          try{
            if($request->is_fixed=='1'){
                $request['is_fixed']='1';
            }else{
                $request['is_fixed']='0';
            }
            $topOffer=RetailerOfferSection::findOrFail($id);
            (new RetailerOfferAction($request))->update($topOffer);
            session()->flash('success',"new Retailer Offer Updated successfully");
            DB::commit();
            return redirect()->route('retailer_offer.index');

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
    public function destroy($id)
    {
        $topOffer=RetailerOfferSection::findOrFail($id);
        try{
             $topOffer->delete();
              session()->flash('success',"Retailer Offer deleted successfully");
            return redirect()->route('retailer_offer.index');
        } catch (\Throwable $th) {
               session()->flash('error',$th->getMessage());
            return redirect()->back()->withInput();
        }
    }
}
