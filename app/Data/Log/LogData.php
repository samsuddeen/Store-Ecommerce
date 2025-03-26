<?php
namespace App\Data\Log;

use App\Models\Log\Log;
use Illuminate\Support\Arr;

class LogData
{
    protected $filters;
    function __construct($filters=[])
    {
        $this->filters = $filters;
    }
    public function getData()
    {   
        $logs = Log::when(Arr::get($this->filters, 'type'), function($q, $value){
            $q->where('log_model', $value);
        })->when(Arr::get($this->filters, 'year'), function($q, $value){
            $q->whereYear('created_at', $value);
        })->when(Arr::get($this->filters, 'month'), function($q, $value){
            $q->whereMonth('created_at', $value);
        })->when(Arr::get($this->filters, 'log_id'), function($q, $value){
            $q->where('log_id', $value);
        })->get();
        return $logs;
    }
}