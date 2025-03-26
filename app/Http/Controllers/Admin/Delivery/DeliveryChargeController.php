<?php

namespace App\Http\Controllers\Admin\Delivery;

use Illuminate\Http\Request;
use App\Models\DeliveryCharge;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\DeliveryChargeRequest;
use App\Models\DeliveryRoute;
use App\Models\Logistics;

class DeliveryChargeController extends Controller
{
    function __construct()
    {
        $this->middleware(['permission:delivery-charge-read'], ['only' => ['index']]);
        $this->middleware(['permission:delivery-charge-create'], ['only' => ['create', 'store']]);
        $this->middleware(['permission:delivery-charge-edit'], ['only' => ['edit', 'update']]);
        $this->middleware(['permission:delivery-charge-delete'], ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("admin.delivery-charge.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $deliveryCharge = new DeliveryCharge();
        $deliveryRoutes = DeliveryRoute::latest()->get();
        return view("admin.delivery-charge.form",compact("deliveryCharge", "deliveryRoutes"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DeliveryChargeRequest $request)
    {
        // dd($request->all());
        // return $request;
        DB::beginTransaction();
        $input = $request->input();
        $input['delivery_route_id']=$request->route;
        $input['user_id']=$request->user()->id;
        // $logistic = Logistics::where('logistic_name', 'Vaccino')->first();
        // $input['logistic_id']=$logistic->id;
        try{
            DeliveryCharge::create($input);
            session()->flash('success',"new Delivery Charge created successfully");
            DB::commit();
            return redirect()->route('delivery-charge.index');
        } catch (\Throwable $th) {
            session()->flash('error',$th->getMessage());
            DB::rollback();
            return redirect()->back()->withInput();

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DeliveryCharge  $deliveryCharge
     * @return \Illuminate\Http\Response
     */
    public function show(DeliveryCharge $deliveryCharge)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DeliveryCharge  $deliveryCharge
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $deliveryCharge = DeliveryCharge::find($id);
        $deliveryRoutes = DeliveryRoute::latest()->get();
        return view("admin.delivery-charge.form",compact("deliveryCharge", "deliveryRoutes"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DeliveryCharge  $deliveryCharge
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
         DB::beginTransaction();
         $deliveryCharge = DeliveryCharge::find($id);
         $input = $request->all();
         $input['delivery_route_id']=$request->route;
         $input['user_id']=$request->user()->id;
          try{
            $deliveryCharge->update($input);
            session()->flash('success',"new Delivery Charge created successfully");
            DB::commit();
            return redirect()->route('delivery-charge.index');

        } catch (\Throwable $th) {
            session()->flash('error',$th->getMessage());
            DB::rollback();
            return redirect()->back()->withInput();

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DeliveryCharge  $deliveryCharge
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeliveryCharge $id)
    {
        $deliveryCharge = DeliveryCharge::findOrFail($id);
        try{
             $deliveryCharge->delete();
              session()->flash('success',"Delivery Charge deleted successfully");
            return redirect()->route('delivery-charge.index');
        } catch (\Throwable $th) {
               session()->flash('error',$th->getMessage());
            return redirect()->back()->withInput();
        }
    }
}
