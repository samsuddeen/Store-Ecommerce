<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Location;

class LocationController extends Controller
{
    public function location()
    {
        // return response()->json('success',200);

        $location = Location::where('publishStatus', 1)->get();

        $location->makeHidden([
            "deleted_at",
            "created_at",
            "updated_at"
        ]);

        return response()->json($location, 200);
    }
}
