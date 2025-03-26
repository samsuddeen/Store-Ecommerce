<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{
    protected $setting = null;

    public function __construct(Setting $setting)
    {
        $this->setting = $setting;
    }

    public function siteDetails(Request $request)
    {
        $this->setting = $this->setting->get();

        if ($this->setting) {
            $response = [
                'error' => false,
                'data' => $this->setting,
                'msg' => 'Company Details'
            ];
            return response()->json($response, 200);
        } else {
            $response = [
                'error' => true,
                'data' => null,
                'msg' => 'Sorry !! No Data Found'
            ];
            return response()->json($response, 500);
        }
    }
}
