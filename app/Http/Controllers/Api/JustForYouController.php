<?php

namespace App\Http\Controllers\Api;

use App\Data\JustForYou\JustForYouData;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class JustForYouController extends Controller
{
    //
    public function index(Request $request)
    {
        
        $filters = $request->all();
        // $city = $request->query('city', 'Kathmandu');
        $data = (new JustForYouData())->getIndexData();
        $response = [
            'status' => 200,
            'msg' => 'Just For You',
            'data' =>  $data,
        ];
        return response()->json($response, 200);
    }

    public function index1(Request $request)
    {
        
        $filters = $request->all();
        $city = $request->query('city', 'Kathmandu');
        $data = (new JustForYouData())->getIndexData1($city);
        $response = [
            'status' => 200,
            'msg' => 'Just For You',
            'data' =>  $data,
        ];
        return response()->json($response, 200);
    }

    public function getProduct(Request $request, $slug)
    {
        
        $filters = $request->all();
        
        $data  = (new JustForYouData($filters))->getSlugData($slug);
        $response = [
            'status' => 200,
            'msg' => 'Just For You',
            'data' =>  $data,
        ];
        return response()->json($response, 200);
    }
}
