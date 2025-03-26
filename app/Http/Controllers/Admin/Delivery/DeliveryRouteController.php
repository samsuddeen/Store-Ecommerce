<?php

namespace App\Http\Controllers\Admin\Delivery;

use App\Data\Hub\HubData;
use App\Models\Local;
use App\Models\Location;
use Illuminate\Http\Request;
use App\Models\DeliveryRoute;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\DeliveryRouteRequest;


class DeliveryRouteController extends Controller
{
    function __construct()
    {
        $this->middleware(['permission:delivery-route-read'], ['only' => ['index']]);
        $this->middleware(['permission:delivery-route-create'], ['only' => ['create', 'store']]);
        $this->middleware(['permission:delivery-route-edit'], ['only' => ['edit', 'update']]);
        $this->middleware(['permission:delivery-route-delete'], ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("admin.delivery-route.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $deliveryRoute = new DeliveryRoute();
        $locations = Location::latest()->get();
        $areas = Local::latest()->get();
        return view("admin.delivery-route.form",compact("deliveryRoute", "locations",'areas'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DeliveryRouteRequest $request)
    {
        $deliveryRoute = DeliveryRoute::where(['from'=>$request->from, 'to'=>$request->to])->first();
        if($deliveryRoute){
            session()->flash('error', 'This Route Already Exist');
            return back();
        }
        DB::beginTransaction();
        $input = $request->all();
        $input['user_id']=$request->user()->id;
        try{
            DeliveryRoute::create($input);
           session()->flash('success',"new DeliveryRoute created successfully");
            DB::commit();
            return redirect()->route('delivery-route.index');
        } catch (\Throwable $th) {
           session()->flash('error',$th->getMessage());
            DB::rollback();
            return redirect()->back()->withInput();

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DeliveryRoute  $deliveryRoute
     * @return \Illuminate\Http\Response
     */
    public function show(DeliveryRoute $deliveryRoute)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DeliveryRoute  $deliveryRoute
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $areas = Location::latest()->get();
        $deliveryRoute = DeliveryRoute::findOrFail($id);
        $hubs = (new HubData())->getData();
        $locations = Location::latest()->get();
        return view("admin.delivery-route.form",compact("deliveryRoute", "locations","areas", 'hubs'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DeliveryRoute  $deliveryRoute
     * @return \Illuminate\Http\Response
     */
    public function update(DeliveryRouteRequest $request, $id)
    {
        
        // $deliveryRoute = DeliveryRoute::where(['from'=>$request->from, 'to'=>$request->to])->first();
        $deliveryRoute = DeliveryRoute::where(['hub_id'=>$request->from, 'location_id'=>$request->to])->first();

        if($deliveryRoute){
            session()->flash('error', 'This Route Already Exist');
            return back();
        }

         DB::beginTransaction();
         $input = $request->all();
         $input['hub_id']=$request->from;
         $input['location_id']=$request->to;
         $input['user_id']=$request->user()->id;
        //  dd($input);
         $deliveryRoute = DeliveryRoute::findOrFail($id);
          try{
           $deliveryRoute->update($input);
           session()->flash('success',"new DeliveryRoute created successfully");
            DB::commit();
            return redirect()->route('delivery-route.index');

        } catch (\Throwable $th) {
           session()->flash('error',$th->getMessage());
            DB::rollback();
            return redirect()->back()->withInput();

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DeliveryRoute  $deliveryRoute
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deliveryRoute = DeliveryRoute::findOrFail($id);
        try{
             $deliveryRoute->delete();
             session()->flash('success',"DeliveryRoute deleted successfully");
            return redirect()->route('delivery-route.index');
        } catch (\Throwable $th) {
              session()->flash('error',$th->getMessage());
            return redirect()->back()->withInput();
        }
    }
}
