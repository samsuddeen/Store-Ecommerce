<?php
namespace App\Data\Location;

use App\Models\City;
use App\Models\Local;
use Illuminate\Support\Arr;

class LocalData
{
    protected $filters;
    protected $locals;
    function __construct($filters)
    {
        $this->filters = $filters;
    }
    public function getData()
    {
        $this->initializeData();
        return $this->locals;
    }
    private function initializeData()
    {
        $this->locals = City::when(Arr::get($this->filters, 'title'), function($q, $value){
            $q->where('city_name', $value);
        })->when(Arr::get($this->filters, 'district_id'), function($q, $value){
            $q->where('dist_id', $value);
        })->orderBy('city_name')->get();
    }
}