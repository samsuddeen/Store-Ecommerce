<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Page;

class PageController extends Controller
{
    public function page(Request $request)
    {
        $page = Page::where('status', 'active')->get();
        $response = [
            'error' => false,
            'data' => $page,
            'msg' => 'All Page List'
        ];
        return response()->json($response, 200);
    }

    public function detailPage(request $request, $slug)
    {


        $page = Page::where('slug', $slug)->where('status', 'active')->first();

        if (!$page) {
            $response = [
                'error' => true,
                'data' => null,
                'msg' => 'Sorry !! Page Not Found !!'
            ];
            return response()->json($response, 500);
        }

        $response = [
            'error' => false,
            'data' => $page,
            'msg' => 'Detail Page Content'
        ];
        return response()->json($response, 200);
    }
}
