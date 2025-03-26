<?php
namespace App\Data\Location;

use App\Models\District;
use Illuminate\Support\Arr;

class DistrictData
{
    protected $filters;
    protected $districts=[];
    function __construct($filters)
    {
        $this->filters = $filters;
    }

    public function getData()
    {
        $this->initializeData();
        return $this->districts;
    }
    private function initializeData()
    {
        $this->districts = District::when(Arr::get($this->filters, 'title'), function($q, $value){
            $q->where('np_name', $value);
        })->when(Arr::get($this->filters, 'province_id'), function($q, $value){
            $q->where('province', $value);
        })->orderBy('province')->get();
    }
}