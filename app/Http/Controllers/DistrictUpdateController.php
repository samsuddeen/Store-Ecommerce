<?php

namespace App\Http\Controllers;

use App\Models\District;
use App\Models\Province;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Local;

class DistrictUpdateController extends Controller
{
    protected $folder_name = "admin.area.form.";
    public function edit(Request $request, $id)
    {
        $district = District::where('id', $id)->first();
        $provinces = Province::all();
        return view($this->folder_name . 'districts', compact('district', 'provinces'));
    }

    public function update(Request $request, $id)
    {
        $district = District::where('id', $id)->first();        
        $input = $request->all();
        try {
        $district->update($input);
        return redirect()->route('district.index')->with('success', 'Successfully Updated');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'OOPs, Try again.');
        }
    }
}
