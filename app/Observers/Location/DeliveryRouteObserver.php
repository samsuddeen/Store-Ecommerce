<?php
namespace App\Observers\Location;

use App\Models\Admin\Hub\Hub;
use App\Models\DeliveryRoute;
use App\Models\Location;
use Illuminate\Http\Request;

class DeliveryRouteObserver
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
            DeliveryRoute::create([
                'hub_id'=>$this->hub->id,
                'location_id'=>$id,
                'is_location'=>true,
                'charge'=>$this->request->charge,
            ]);
        }else{
            DeliveryRoute::create([
                'hub_id'=>$this->hub->id,
                'local_id'=>$id,
                'is_location'=>false,
                'charge'=>$this->request->charge,
            ]);
        }
    }
}