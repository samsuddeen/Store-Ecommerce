<?php

namespace App\Http\Controllers;

use App\Data\Hub\NearPlaceData;
use App\Models\Admin\Hub\HubNearPlace;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class HubNearPlaceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filters = $request->all();
        $data = (new NearPlaceData($filters))->getData();
        return view("admin.hub.place.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         $hubNearPlace = new HubNearPlace();
        return view("admin.HubNearPlace.form",compact("hubNearPlace"));
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
            HubNearPlace::create($request->all());
            session()->flash('success',"new HubNearPlace created successfully");
            DB::commit();
            return redirect()->route('HubNearPlace.index');
        } catch (\Throwable $th) {
            session()->flash('error',$th->getMessage());
            DB::rollback();
            return redirect()->back()->withInput();

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Admin\Hub\HubNearPlace  $hubNearPlace
     * @return \Illuminate\Http\Response
     */
    public function show(HubNearPlace $hubNearPlace)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Admin\Hub\HubNearPlace  $hubNearPlace
     * @return \Illuminate\Http\Response
     */
    public function edit(HubNearPlace $hubNearPlace)
    {
        return view("admin.HubNearPlace.form",compact("hubNearPlace"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Admin\Hub\HubNearPlace  $hubNearPlace
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, HubNearPlace $hubNearPlace)
    {
         DB::beginTransaction();
          try{
           $hubNearPlace->update($request->all());
            session()->flash('success',"new HubNearPlace created successfully");
            DB::commit();
            return redirect()->route('HubNearPlace.index');

        } catch (\Throwable $th) {
            session()->flash('error',$th->getMessage());
            DB::rollback();
            return redirect()->back()->withInput();

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Admin\Hub\HubNearPlace  $hubNearPlace
     * @return \Illuminate\Http\Response
     */
    public function destroy(HubNearPlace $hubNearPlace)
    {
        try{
            $id = $hubNearPlace->hub->id;
            $hubNearPlace->delete();
            session()->flash('success',"HubNearPlace deleted successfully");
            return redirect()->route('near-place.index', ['hub_id'=>$id]);
        } catch (\Throwable $th) {
              session()->flash('error',$th->getMessage());
            return redirect()->back()->withInput();
        }
    }
}
