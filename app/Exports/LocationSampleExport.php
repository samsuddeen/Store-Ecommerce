<?php

namespace App\Exports;

use Illuminate\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;


class LocationSampleExport implements FromView
{
    protected $data;
    function __construct($data)
    {
        $this->data = $data;
    }
    public function view(): View
    {
        return view('export.location.location-export', $this->data);
    }
  
}
