<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\District;
use App\Models\Local;
use App\Models\Province;
use GrahamCampbell\ResultType\Success;
use Illuminate\Http\Request;

class LocalLevelController extends Controller
{
    protected $folder_name = "admin.area.form.";

    public function edit(Request $request, $id)
    {        
        $local = City::where('id', $id)->first();
        $provinces = Province::all();
        $districts = District::all();
        return view($this->folder_name . 'locals', compact('local', 'provinces', 'districts'));
    }

    public function update(Request $request, $id)
    {
        $local = City::where('id', $id)->first();
        $input = $request->all();
        try {
            $local->update($input);
            
            return redirect()->route('local.index')->with('success', 'Successfully Updated');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'OOPs, Try again.');
        }
    }
}
