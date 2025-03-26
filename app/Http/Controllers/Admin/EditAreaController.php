<?php

namespace App\Http\Controllers\Admin;

use App\Models\Local;
use App\Models\District;
use App\Models\Province;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use function PHPSTORM_META\elementType;

class EditAreaController extends Controller
{
    public function provinceStatus($id)
    {
        $province = Province::where('id', $id)->first();

        if ($province->publishStatus == 0) {
            $province->publishStatus = 1;
        } else {
            $province->publishStatus = 0;
        }
        $status = $province->save();
        if ($status == 1) {
            session()->flash('success', 'Successfully Chnaged publish status.');
        }
        return back();
    }

    public function districtStatus(Request $request)
    {
        $district = District::where('id', $request->district_id)->first();

        if ($district->publishStatus == 0) {
            $district->publishStatus = 1;
        } else {
            $district->publishStatus = 0;
        }

        $status = $district->save();
        if ($district->publishStatus == 0) {
            $status_test = 'In-Active';
        } else {
            $status_test = 'Active';
        }

        if ($status == 1) {
            return response()->json([
                'error' => false,
                'data' => $status_test,
                'id' => $district->id,
                'msg' => 'Successfully Chnaged publish status.',
            ], 200);
        } else {
            return response()->json([
                'error' => true,
                'msg' => 'OOPs, Try Again !',
            ], 200);
        }
        return back();
    }

    public function localStatus(Request $request)
    {
        $local = Local::where('id', $request->local_id)->first();

        if ($local->publishStatus == 0) {
            $local->publishStatus = 1;
        } else {
            $local->publishStatus = 0;
        }
        $status = $local->save();

        if ($local->publishStatus == 0) {
            $status_test = 'In-Active';
        } else {
            $status_test = 'Active';
        }
        if ($status == 1) {
            return response()->json([
                'error' => false,
                'data' => $status_test,
                'msg' => 'Successfully Chnaged publish status.',
            ], 200);
        } else {
            return response()->json([
                'error' => true,
                'msg' => 'OOPs, Try Again !',
            ], 200);
        }
        return back();
    }
}
