<?php
namespace App\Http\Controllers\Support;

use App\Data\Location\DistrictData;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AllLocationController extends Controller
{
    //
    public function getDistrict(Request $request)
    {
        $filters = array_merge([], $request->all());
        $districts = (new DistrictData($filters))->getData();
        return response()->json($districts, 200);
    }
}
