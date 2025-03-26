<?php

namespace App\Data\Log;

use App\Models\Log\Log;
use Illuminate\Support\Arr;

class LogReportData
{
    protected $filters;
    public function __construct($filters)
    {
        $this->filters=$filters;
    }

    public function getData()
    {
        $log=Log::latest()->get();
        $data=$log
            ->when(Arr::get($this->filters, 'month'), function ($q, $value) {
                return $q->filter(function ($item) use ($value) {
                    return date('m', strtotime($item->created_at)) == $value;
                });
            })
            ->when(Arr::get($this->filters, 'year'), function ($q, $value) {
                return $q->filter(function ($item) use ($value) {
                    return date('Y', strtotime($item->created_at)) == $value;
                });
            })
            ->when(Arr::get($this->filters, 'type'), function ($q,$value) {
                if($value==='AppModelsSeller')
                {
                    return $q->where('log_model','App\Models\Seller');
                } elseif($value==='AppModelsNew_Customer')
                {
                    return $q->where('log_model','App\Models\New_Customer');
                }
            });
        return $data;
    }
}