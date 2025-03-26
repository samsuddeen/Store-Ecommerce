<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OrderTimeSetting;

class OrderSettingController extends Controller
{
    public function index()
    {
        $settings = OrderTimeSetting::get();
        return view('admin.settings.order',compact('settings'));
    }

    public function update(Request $request, $id)
    {
        $setting = OrderTimeSetting::find($request->setting_id);
        $setting->start_time = $request->start_time;
        $setting->end_time =$request->end_time;
        $setting->day_off = $request->day_off;
        $setting->save();
        return response()->json(['status'=>200, 'Message'=>'Setting updated successfully!']);
    }

}
