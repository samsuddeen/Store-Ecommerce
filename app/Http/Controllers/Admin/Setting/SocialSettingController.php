<?php

namespace App\Http\Controllers\Admin\Setting;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Setting\SocialSetting;
use App\Http\Requests\SocialSettingStoreRequest;
use App\Http\Requests\SocialSettingUpdateRequest;
use Illuminate\Support\Facades\Validator;

class SocialSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("admin.setting.social.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $socialSetting = new SocialSetting();
        return view("admin.setting.social.form",compact("socialSetting"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SocialSettingStoreRequest $request)
    {
        DB::beginTransaction();
        try{
            $input =$request->all();
            $input['slug']=Str::slug($request->title);
            SocialSetting::create($input);
            session()->flash('success',"new Social Setting created successfully");
            DB::commit();
            return redirect()->route('social-setting.index');
        } catch (\Throwable $th) {
            session()->flash('error',$th->getMessage());
            DB::rollback();
            return redirect()->back()->withInput();

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Setting\SocialSetting  $socialSetting
     * @return \Illuminate\Http\Response
     */
    public function show(SocialSetting $socialSetting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Setting\SocialSetting  $socialSetting
     * @return \Illuminate\Http\Response
     */
    public function edit(SocialSetting $socialSetting)
    {
        return view("admin.setting.social.form",compact("socialSetting"));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Setting\SocialSetting  $socialSetting
     * @return \Illuminate\Http\Response
     */
    public function update(SocialSettingUpdateRequest $request, SocialSetting $socialSetting)
    {
         DB::beginTransaction();
          try{
            $input = $request->all();
            $input['slug']=Str::slug($request->title);
            $socialSetting->update($input);
            session()->flash('success',"new Social Setting updated successfully");
            DB::commit();
            return redirect()->route('social-setting.index');
        } catch (\Throwable $th) {
            session()->flash('error',$th->getMessage());
            DB::rollback();
            return redirect()->back()->withInput();

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Setting\SocialSetting  $socialSetting
     * @return \Illuminate\Http\Response
     */
    public function destroy(SocialSetting $socialSetting)
    {
        try{
             $socialSetting->delete();
              session()->flash('success',"Social Setting deleted successfully");
            return redirect()->route('social-setting.index');
        } catch (\Throwable $th) {
               session()->flash('error',$th->getMessage());
            return redirect()->back()->withInput();
        }
    }
    public function updateStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'social_setting_id'=>'required',
        ]);
        if($validator->fails()){
            return back()->withInput()->with('error', 'Something is wrong');
        }
        DB::beginTransaction();
        try {
            $socialSetting = SocialSetting::where('id', $request->social_setting_id)->update(['status'=>$request->status]);
            DB::commit();
            session()->flash('success', 'Successfully Status has been changed');
            return redirect()->route('social-setting.index');
        } catch (\Throwable $th) {
            DB::rollBack();
            session()->flash('error', 'Sorry something is wrong');
            return back()->withInput();
        }

    }
}
