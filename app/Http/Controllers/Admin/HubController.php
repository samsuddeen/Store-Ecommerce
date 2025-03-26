<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Hub\HubAction;
use App\Models\Admin\Hub\Hub;
use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\Local;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;


class HubController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:hubs-read'], ['only' => ['index']]);
        $this->middleware(['permission:hubs-create'], ['only' => ['create', 'store']]);
        $this->middleware(['permission:hubs-edit'], ['only' => ['edit', 'update']]);
        $this->middleware(['permission:hubs-delete'], ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("admin.hub.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [];
        $hub = new Hub();
        $data['hub'] = $hub;
        $data['districts'] = District::orderBy('np_name')->get();
        return view("admin.hub.form", $data);
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
        try {
            (new HubAction($request))->store();
            session()->flash('success', "new Hub created successfully");
            DB::commit();
            return redirect()->route('hub.index');
        } catch (\Throwable $th) {
            session()->flash('error', $th->getMessage());
            DB::rollback();
            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Admin\Hub\Hub  $hub
     * @return \Illuminate\Http\Response
     */
    public function show(Hub $hub)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Admin\Hub\Hub  $hub
     * @return \Illuminate\Http\Response
     */
    public function edit(Hub $hub)
    {
        return view("admin.hub.edit", compact("hub"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Admin\Hub\Hub  $hub
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Hub $hub)
    {
        DB::beginTransaction();
        try {
            (new HubAction($request))->update($hub);
            session()->flash('success', "new Hub created successfully");
            DB::commit();
            return redirect()->route('hub.index');
        } catch (\Throwable $th) {
            session()->flash('error', $th->getMessage());
            DB::rollback();
            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Admin\Hub\Hub  $hub
     * @return \Illuminate\Http\Response
     */
    public function destroy(Hub $hub)
    {
        try {
            $hub->nearPlace()->delete();
            $hub->delete();
            session()->flash('success', "Hub deleted successfully");
            return redirect()->route('hub.index');
        } catch (\Throwable $th) {
            session()->flash('error', $th->getMessage());
            return redirect()->back()->withInput();
        }
    }
    public function getAddress(Request $request)
    {
        $filters = $request->all();
        $data = [];
        $data['id'] = $request->id;
        if (Arr::get($filters, 'type')) {
            if ($filters['type'] == 'district') {
                $data['data'] = Local::where('dist_id', $filters['id'])->orderBy('local_name')->get();
                return response()->json($data, 200);
            } else {
                $data['data'] = Location::where('local_id', $filters['id'])->orderBy('title')->get();
                return response()->json($data, 200);
            }
        }
    }
}
