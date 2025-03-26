<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Slider;
use Storage;

class SliderController extends Controller
{
    public function slider()
    {
        $slider = Slider::where('publish_status', '1')->where('show_mob',1)->get();
           
      
        $slider->makeHidden([
            'meta_desc',
            'meta_keyword',
            'meta_title',
            'publish_status',
            'delete_status',
            'hide_status',
            'created_at',
            'updated_at'
        ]);

        $response = [
            'error' => false,
            'data' => $slider,
            'msg' => 'Slider List'
        ];
        return response()->json($response, 200);
    }

    public function singleSlider(Request $request, $id)
    {
        $slider = Slider::findOrFail($id);
        $slider->makeHidden([
            'meta_desc',
            'meta_keyword',
            'meta_title',
            'publish_status',
            'delete_status',
            'hide_status',
            'created_at',
            'updated_at'
        ]);
        $response = [
            'error' => false,
            'data' => $slider,
            'msg' => 'Slider Single page'
        ];
        return response()->json($response, 200);
    }
}
