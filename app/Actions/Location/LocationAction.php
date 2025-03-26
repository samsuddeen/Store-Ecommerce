<?php

namespace App\Actions\Location;

use App\Models\Location;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Admin\Hub\Hub;
use App\Observers\Location\NearPlaceObserver;
use App\Observers\Location\DeliveryRouteObserver;

class LocationAction
{
    protected $request;
    protected $input;
    function __construct(Request $request)
    {
        $this->request = $request;
        $this->input = $request->all();
        $this->input['slug'] = Str::slug($request->title) . rand(0000, 9999);
        $this->input['user_id'] = $request->user()->id;
    }
    public function store()
    {
        $this->request['type'] = 'location';
        $hub = Hub::findOrFail($this->request->hub_id);
        $location = Location::create($this->input);
        (new DeliveryRouteObserver($hub, $this->request))->observe($location->id);
        (new NearPlaceObserver($hub, $this->request))->observe($location->id);
    }
}
