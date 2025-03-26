<?php
namespace App\Data\Location;

use App\Models\Province;
use Illuminate\Support\Arr;

class ProvinceData
{
    protected $filters;
    protected $provinces;
    function __construct($filters)
    {
        $this->filters = $filters;
    }
    public function getData()
    {
        $this->initializeData();
        return $this->provinces;
    }
    private function initializeData()
    {
        $this->provinces = Province::when(Arr::get($this->filters, 'title'), function($q, $value){
                        $q->where('eng_name', $value);
        })->orderBy('eng_name')->get();
    }
}