<?php

namespace App\Actions\Hub;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Admin\Hub\Hub;
use App\Models\Admin\Hub\HubNearPlace;

class HubAction
{
    protected $input;
    protected $request;
    function __construct(Request $request)
    {
        $this->input = $request->all();
        $this->request = $request;
        $this->input['slug'] = Str::slug($request->title);
        $this->input['created_by'] = auth()->user()->id;
    }
    public function store()
    {        
        // $hub = Hub::where('title', 'Hilihang Rural-Municipality')->first();
        // dd($hub);
        $hub = Hub::create($this->input);        
        $this->storeLocation($hub);
    }
    public function update(Hub $hub)
    {
        $hub->update($this->input);
    }

    private function storeLocation(Hub $hub)
    {
        if ($this->request->local_id !== null) {
            foreach ($this->request->local_id as $local) {
                HubNearPlace::updateOrCreate(
                    [
                        'hub_id' => $hub->id,
                        'local_id' => $local,
                    ],
                    [
                        'is_location' => false,
                    ]
                );
            }
        }

        if ($this->request->location_id !== null) {
            foreach ($this->request->location_id as $location_id) {
                HubNearPlace::updateOrCreate(
                    [
                        'hub_id' => $hub->id,
                        'location_id' => $location_id,
                    ],
                    [
                        'is_location' => true,
                    ]
                );
            }
        }
    }
}
