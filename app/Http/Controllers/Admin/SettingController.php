<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\Setting\Seo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::get();        
        $data['settings'] = $settings;
        $data['seo'] = Seo::latest()->first();
        return view('admin.settings.index', $data);
    }

    public function syncSetting(Request $request)
    {
        // return $request->all();
        
    //   return  $newName = str_replace(' ', '_', $request->app_logolo); // Replace spaces with underscores
        DB::beginTransaction();
        try {
            foreach ($request->all() as $key => $value) {
                Setting::where('key', $key)->update(['value' => $value]);
            }

            DB::commit();
            session()->flash('success', 'Settings Updated Successfully');
            return redirect()->back();
        } catch (\Throwable $th) {
            session()->flash('error', $th->getMessage());
            DB::rollback();
            return redirect()->back()->withInput();
        }
    }

    public function syncMetaData(Request $request, $id=null)
    {
        $seo = new Seo();
        if($id !== null){
            $seo = Seo::find($id);
        }
        DB::beginTransaction();
        try {
            if($id !==null){
                $seo->update($request->all());
            }else{
                $seo->fill($request->all());
                $seo->save();
            }
            DB::commit();
            session()->flash('success', 'Settings Updated Successfully');
            return redirect()->back();
        } catch (\Throwable $th) {
            session()->flash('error', $th->getMessage());
            DB::rollback();
            return redirect()->back()->withInput();
        }
    }
}
