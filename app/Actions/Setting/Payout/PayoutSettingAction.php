<?php
namespace App\Actions\Setting\Payout;

use App\Models\Setting\PayoutSetting;
use Illuminate\Http\Request;

class PayoutSettingAction 
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
        PayoutSetting::create([
            'period'=>$this->request->period,
            'status'=>$this->request->status,
            'is_default'=>$this->request->is_default ?? false,
        ]);
    }
    public function update(PayoutSetting $payoutSetting)
    {
        $this->observe();
        $payoutSetting->update([
            'period'=>$this->request->period,
            'status'=>$this->request->status,
            'is_default'=>$this->request->is_default ?? false,
        ]);
    }
    private function observe()
    {
        if($this->request->has('is_default')){
            PayoutSetting::where('is_default', true)->update([
                'is_default'=>false,
            ]);
        }
    }
}