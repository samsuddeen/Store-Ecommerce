<?php
namespace App\Actions\Setting\Backup;

use App\Models\Backup\BackupPeriod;
use Illuminate\Http\Request;

class BackupPeriodAction 
{
    protected $request;
    protected $input;
    function __construct(Request $request)
    {
        $this->request = $request;
        $this->input = $request->all();
    }
    public function store()
    {
        $this->observe();
        BackupPeriod::create([
            'period'=>$this->request->period,
            'status'=>$this->request->status,
            'is_default'=>$this->request->is_default ?? false,
        ]);
    }
    public function update(BackupPeriod $backupPeriod)
    {
        $this->observe();
        $backupPeriod->update([
            'period'=>$this->request->period,
            'status'=>$this->request->status,
            'is_default'=>$this->request->is_default ?? false,
        ]);
    }
    private function observe()
    {
        if($this->request->has('is_default')){
            BackupPeriod::where('is_default', true)->update([
                'is_default'=>false,
            ]);
        }
    }
}