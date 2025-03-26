<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdvertisementRequest;
use App\Models\Advertisement;
use App\Models\Position;
use Illuminate\Http\Request;

class AdvertisementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $advertisement = Advertisement::paginate(20);
        return view("admin.advertisement.index", compact("advertisement"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $advertisement = new Advertisement();
        $positions = Position::get();
        $selectedPositions = [];
        return view("admin.advertisement.form", compact("advertisement", 'positions', 'selectedPositions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AdvertisementRequest $request)
    {
        try {
            $data = $request->validated();
            $advertisement  = Advertisement::create($data);
            if($advertisement->ad_type == 'General')
            {
                $advertisement->positions()->sync($data['positions'] ?? null);
            }
            session()->flash('success', "new Advertisement created successfully");
            return redirect()->route('advertisement.index');
        } catch (\Throwable $th) {
            session()->flash('error', $th->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Advertisement  $advertisement
     * @return \Illuminate\Http\Response
     */
    public function show(Advertisement $advertisement)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Advertisement  $advertisement
     * @return \Illuminate\Http\Response
     */
    public function edit(Advertisement $advertisement)
    {
        $positions = Position::get();
        $selectedPositions = $advertisement->positions()->pluck('positions.id')->toArray();
        return view("admin.advertisement.form", compact("advertisement", 'positions', 'selectedPositions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Advertisement  $advertisement
     * @return \Illuminate\Http\Response
     */
    public function update(AdvertisementRequest $request, Advertisement $advertisement)
    {
        $data = $request->validated();
        try {
            $advertisement->update($data);
            if($data['ad_type'] == 'General'){
                $advertisement->positions()->sync($data['positions']);
            }else{
                $advertisement->positions()->detach($data['positions']);
            }
            session()->flash('success', "new Advertisement created successfully");
            return redirect()->route('advertisement.index');
        } catch (\Throwable $th) {
            session()->flash('error', $th->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Advertisement  $advertisement
     * @return \Illuminate\Http\Response
     */
    public function destroy(Advertisement $advertisement)
    {
        try {
            $advertisement->delete();
            session()->flash('success', "Advertisement deleted successfully");
            return redirect()->route('advertisement.index');
        } catch (\Throwable $th) {
            session()->flash('error', $th->getMessage());
            return redirect()->back()->withInput();
        }
    }
}
