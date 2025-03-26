<?php
namespace App\Observers\Location;

use Illuminate\Http\Request;
use App\Models\Admin\Hub\Hub;
use App\Models\Admin\Hub\HubNearPlace;

class NearPlaceObserver
{
    protected $hub;
    protected $request;
    function __construct(Hub $hub, Request $request)
    {
        $this->hub = $hub;
        $this->request = $request;
    }
    public function observe($id)
    {
        if($this->request->type == 'location'){
            HubNearPlace::updateOrCreate([
                'hub_id'=>$this->hub->id,
                'location_id'=>$id,
            ],
            [
                'is_location'=>true,
                'charge'=>$this->request->charge,
            ]);
        }else{
            HubNearPlace::updateOrCreate([
                'hub_id'=>$this->hub->id,
                'local_id'=>$id,
            ],
            [
                'is_location'=>false,
                'charge'=>$this->request->charge,
            ]);
        }
    }
}