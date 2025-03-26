<?php
namespace App\Actions\Admin;

use App\Models\Brand;
use Illuminate\Http\Request;

class BrandStoreAction{

    protected $request;
    public function __construct(Request $request)
    {
        $this->request=$request;
    }

    public function storeBrand()
    {
        $data=Brand::create([
            'name'=>$this->request->name,
            'logo'=>$this->request->image,
            'status'=>$this->request->status
        ]);
        return $data;
        
    }
}