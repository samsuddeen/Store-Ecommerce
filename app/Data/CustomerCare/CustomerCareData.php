<?php
namespace App\Data\CustomerCare;

use App\Models\CustomerCare;
use App\Models\CustomerCarePage;

class CustomerCareData{

    protected $id;
    protected $dataValue;
    public function __construct($id=[])
    {
        $this->id=$id;
        $this->dataValue=CustomerCarePage::where('id',$this->id)->first();
    }
    public function getData()
    {
        $data['page']=CustomerCarePage::get();
        return $data;
    }

    public function formData()
    {
        return $this->dataValue;
    }
}