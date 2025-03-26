<?php
namespace App\Data\Location;

use App\Models\Location;
use Illuminate\Support\Arr;

class LocationData
{
    protected $filters;
    protected $locations;
    function __construct($filters)
    {
        $this->filters = $filters;
    }
    public function getData()
    {
        $this->initializeData();
        return $this->locations;
    }
    private function initializeData()
    {
        $this->locations = Location::when(Arr::get($this->filters, 'title'), function($q, $value){
            $q->where('title', $value);
        })->when(Arr::get($this->filters, 'local_id'), function($q, $value){
            $q->where('local_id', $value);
        })->orderBy('title')->get();
    }
}