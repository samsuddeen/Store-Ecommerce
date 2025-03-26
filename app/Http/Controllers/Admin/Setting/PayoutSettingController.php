<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Actions\Setting\Payout\PayoutSettingAction;
use App\Enum\Setting\PayoutPeriodEnum;
use App\Models\Setting\PayoutSetting;
use App\Http\Controllers\Controller;
use App\Http\Requests\PayoutSettingRequest;
use App\Http\Requests\PayoutSettingUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PayoutSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("admin.setting.payout.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['payoutSetting'] = new PayoutSetting();
        $data['periods'] = (new PayoutPeriodEnum)->getAllValues();
        return view("admin.setting.payout.form", $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PayoutSettingRequest $request)
    {
        DB::beginTransaction();
        try{
            (new PayoutSettingAction($request))->store();
            session()->flash('success',"new PayoutSetting created successfully");
            DB::commit();
            return redirect()->route('payout-setting.index');
        } catch (\Throwable $th) {
            session()->flash('error',$th->getMessage());
            DB::rollback();
            return redirect()->back()->withInput();

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Setting\PayoutSetting  $payoutSetting
     * @return \Illuminate\Http\Response
     */
    public function show(PayoutSetting $payoutSetting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Setting\PayoutSetting  $payoutSetting
     * @return \Illuminate\Http\Response
     */
    public function edit(PayoutSetting $payoutSetting)
    {
        $data['payoutSetting'] = $payoutSetting;
        $data['periods'] = (new PayoutPeriodEnum)->getAllValues();
        return view("admin.setting.payout.form",$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Setting\PayoutSetting  $payoutSetting
     * @return \Illuminate\Http\Response
     */
    public function update(PayoutSettingUpdateRequest $request, PayoutSetting $payoutSetting)
    {
         DB::beginTransaction();
          try{
            (new PayoutSettingAction($request))->update($payoutSetting);
            session()->flash('success',"new PayoutSetting created successfully");
            DB::commit();
            return redirect()->route('payout-setting.index');

        } catch (\Throwable $th) {
            session()->flash('error',$th->getMessage());
            DB::rollback();
            return redirect()->back()->withInput();

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Setting\PayoutSetting  $payoutSetting
     * @return \Illuminate\Http\Response
     */
    public function destroy(PayoutSetting $payoutSetting)
    {
        if($payoutSetting->is_default == true){
            session()->flash('error', 'Sorry this is default Payout Period');
            return redirect()->route('payout-setting.index');
        }
        try{
             $payoutSetting->delete();
              session()->flash('success',"PayoutSetting deleted successfully");
            return redirect()->route('payout-setting.index');
        } catch (\Throwable $th) {
               session()->flash('error',$th->getMessage());
            return redirect()->back()->withInput();
        }
    }
    public function changeStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'payout_setting_id'=>'required|exists:payout_settings,id',
            'status'=>'required:',
            'status'=>Rule::in([1,2]),
        ]);
        DB::beginTransaction();
        $payoutSetting = PayoutSetting::findOrFail($request->payout_setting_id);
        if($payoutSetting->is_default == true){
            session()->flash('error', 'Sorry this is default Payout Period');
            return redirect()->route('payout-setting.index');
        }
        try {
            $payoutSetting->update([
                'status'=>$request->status,
            ]);
            DB::commit();
            session()->flash('success', 'Successfully Status has been changed');
            return redirect()->route('payout-setting.index');
        } catch (\Throwable $th) {
           DB::rollBack();
           session()->flash('error', 'Something is wrong');
           return back()->withInput();
        }
    }
}
