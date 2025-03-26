<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Location;
use App\Data\Hub\HubData;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Data\Filter\FilterData;
use App\Data\Location\LocalData;
use Illuminate\Support\Facades\DB;
use App\Data\Location\DistrictData;

use App\Data\Location\LocationData;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LocationSampleExport;
use App\Http\Requests\LocationRequest;
use App\Actions\Location\LocationAction;
use App\Imports\LocationImport;
use App\Models\DeliveryRoute;
use App\Models\Admin\Hub\HubNearPlace;
class LocationController extends Controller
{
    protected $delivery_route=null;
    protected $hub_near_place=null;
    function __construct(DeliveryRoute $delivery_route,HubNearPlace $hub_near_place)
    {
        $this->delivery_route=$delivery_route;
        $this->hub_near_place=$hub_near_place;
        $this->middleware(['permission:location-read'], ['only' => ['index']]);
        $this->middleware(['permission:location-create'], ['only' => ['create', 'store']]);
        $this->middleware(['permission:location-edit'], ['only' => ['edit', 'update']]);
        $this->middleware(['permission:location-delete'], ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("admin.location.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $filters = (new FilterData($request))->getData();
        $data['locals'] = (new LocalData($filters))->getData();
        $data['location'] = new Location();
        $data['hubs'] = (new HubData($filters))->getData();
        
        return view("admin.location.form", $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LocationRequest $request)
    {
        
        DB::beginTransaction();
        try{
            (new LocationAction($request))->store();
            session()->flash('success',"new Location created successfully");
            DB::commit();
            return redirect()->route('location.index');
        } catch (\Throwable $th) {
            session()->flash('error',$th->getMessage());
            DB::rollback();
            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function show(Location $location)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request,Location $location)
    {
        $filters = (new FilterData($request))->getData();
        $data['locals'] = (new LocalData($filters))->getData();
        $data['location'] = new Location();
        $data['hubs'] = (new HubData($filters))->getData();
        return view("admin.location.form",compact("location"),$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function update(LocationRequest $request, Location $location)
    {        
         DB::beginTransaction();
         $input = $request->all();
         
         $input['slug']=Str::slug($request->title);
         $input['user_id']=$request->user()->id;
          try{
            $location->update($input);
            $this->delivery_route=$this->delivery_route->where('location_id',$location->id)->first();
            $input['location_id']=$location->id;            
            $this->delivery_route->update($input);
            $this->hub_near_place=$this->hub_near_place->where('location_id',$location->id)->first();
            $this->hub_near_place->update($input);

            session()->flash('success',"The location updated successfully");
            DB::commit();
            return redirect()->route('location.index');

        } catch (\Throwable $th) {
            session()->flash('error',$th->getMessage());
            DB::rollback();
            return redirect()->back()->withInput();

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function destroy(Location $location)
    {
        try{
             $location->delete();
              session()->flash('success',"Location deleted successfully");
            return redirect()->route('location.index');
        } catch (\Throwable $th) {
               session()->flash('error',$th->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function exportSample(Request $request)
    {
        return response()->download('sample/sample-location.xlsx'); 
    }
    public function export(Request $request)
    {
        $filters = (new FilterData($request))->getData();
        $locations = (new LocationData($filters))->getData();
        $final_locations = [];
        foreach($locations as $location){
            $locations = (new LocationData($filters))->getData();
            $final_locations[] = [
                'from'=>$location->deliveryRoute->from->title,
                'to'=>$location->title,
                'local_address'=>$location->local->local_name,
                'charge'=>$location->deliveryRoute->charge,
            ];
        }
        $data['locations'] = $final_locations;
        $file_name = 'sample-location-'.Carbon::now()->toDateString();
        return Excel::download(new LocationSampleExport($data), $file_name . '.xlsx');
    }
    public function showImportForm()
    {
        return view('admin.location.import');
    }
    public function import(Request $request)
    {
        DB::beginTransaction();
        try {
            $import = new LocationImport();
            Excel::import($import, $request->file);
            $errors = $import->getErrors();
            if(count($errors) > 0){
                DB::rollBack();
                return back()->with('error', json_encode($errors));
            }else{
                DB::commit();
                return redirect()->route('location.index')->with('success', 'Succesfully Location Imported');
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', 'Something is wrong');
        }
    }
}
