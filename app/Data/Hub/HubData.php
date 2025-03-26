<?php
namespace App\Data\Hub;

use App\Models\Admin\Hub\Hub;
use Illuminate\Support\Arr;

class HubData
{
    protected $filters;
    protected $hubs;
    function __construct($filters=[])
    {
        $this->filters = $filters;
    }
    public function getData()
    {
        $this->initializeData();
        return $this->hubs;
    }
    private function initializeData()
    {
        $this->hubs = Hub::when(Arr::get($this->filters, 'title'), function($q, $value){
                $q->where('title', $value);
        })->when(Arr::get($this->filters, 'address'), function($q, $value){
            $q->where('address', $value);
        })->orderBy('title')->get();
    }
}