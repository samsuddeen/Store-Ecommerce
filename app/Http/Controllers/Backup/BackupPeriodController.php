<?php

namespace App\Http\Controllers\Backup;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Models\Backup\BackupPeriod;
use App\Http\Controllers\Controller;
use App\Enum\Setting\PayoutPeriodEnum;
use App\Http\Requests\BackupStoreRequest;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\BackupUpdateRequest;
use App\Actions\Setting\Backup\BackupPeriodAction;

class BackupPeriodController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("admin.setting.backup.period.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $backupPeriod = new BackupPeriod();
        $data['backupPeriod'] = new backupPeriod();
        $data['periods'] = (new PayoutPeriodEnum)->getAllValues();
        return view("admin.setting.backup.period.form", $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BackupStoreRequest $request)
    {
        DB::beginTransaction();
        try{
            (new BackupPeriodAction($request))->store();
            session()->flash('success',"new BackupPeriod created successfully");
            DB::commit();
            return redirect()->route('backup-period.index');
        } catch (\Throwable $th) {
            session()->flash('error',$th->getMessage());
            DB::rollback();
            return redirect()->back()->withInput();

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Backup\BackupPeriod  $backupPeriod
     * @return \Illuminate\Http\Response
     */
    public function show(BackupPeriod $backupPeriod)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Backup\BackupPeriod  $backupPeriod
     * @return \Illuminate\Http\Response
     */
    public function edit(BackupPeriod $backupPeriod)
    {
        $data['backupPeriod'] = $backupPeriod;
        $data['periods'] = (new PayoutPeriodEnum)->getAllValues();
        return view("admin.setting.backup.period.form",$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Backup\BackupPeriod  $backupPeriod
     * @return \Illuminate\Http\Response
     */
    public function update(BackupUpdateRequest $request, BackupPeriod $backupPeriod)
    {
         DB::beginTransaction();
          try{
            (new BackupPeriodAction($request))->update($backupPeriod);
            session()->flash('success',"new BackupPeriod created successfully");
            DB::commit();
            return redirect()->route('backup-period.index');

        } catch (\Throwable $th) {
            session()->flash('error',$th->getMessage());
            DB::rollback();
            return redirect()->back()->withInput();

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Backup\BackupPeriod  $backupPeriod
     * @return \Illuminate\Http\Response
     */
    public function destroy(BackupPeriod $backupPeriod)
    {


        if($backupPeriod->is_default == true){
            session()->flash('error', 'Sorry this is default Payout Period');
            return redirect()->route('payout-setting.index');
        }
        
        try{
             $backupPeriod->delete();
              session()->flash('success',"BackupPeriod deleted successfully");
            return redirect()->route('backup-period.index');
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
        $backupPeriod = BackupPeriod::findOrFail($request->payout_setting_id);
        if($backupPeriod->is_default == true){
            session()->flash('error', 'Sorry this is default Payout Period');
            return redirect()->route('backup-period.index');
        }
        try {
            $backupPeriod->update([
                'status'=>$request->status,
            ]);
            DB::commit();
            session()->flash('success', 'Successfully Status has been changed');
            return redirect()->route('backup-period.index');
        } catch (\Throwable $th) {
           DB::rollBack();
           session()->flash('error', 'Something is wrong');
           return back()->withInput();
        }
    }
}
