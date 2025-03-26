<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Province;
use Illuminate\Http\Request;

class ProvinceUpdateController extends Controller
{
    protected $folder_name = "admin.area.form.";
    public function edit(Request $request, $id)
    {
        $province = Province::where('id', $id)->first();
        return view($this->folder_name . 'provinces', compact('province'));
    }

    public function update(Request $request, $id)
    {
        $province = Province::where('id', $id)->first();
        $input = $request->all();
        try {
            $province->update($input);
            return redirect()->route('province.index')->with('success', 'Successfully Updated');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'OOPs, Try again.');
        }
    }
}
