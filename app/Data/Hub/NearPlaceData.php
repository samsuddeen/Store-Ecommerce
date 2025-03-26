<?php
namespace App\Data\Hub;

use App\Models\Admin\Hub\HubNearPlace;
use Illuminate\Support\Arr;

class NearPlaceData
{
    protected $filters;
    protected $data=[];
    function __construct($filters)
    {
        $this->filters = $filters;
    }
    public function getData()
    {
        $this->initializeData();
        return $this->data;
    }
    private function initializeData()
    {
        $this->data['places'] = HubNearPlace::when(Arr::get($this->filters, 'hub_id'), function($q, $value){
            $q->where('hub_id', $value);
        })->get();
    }
}