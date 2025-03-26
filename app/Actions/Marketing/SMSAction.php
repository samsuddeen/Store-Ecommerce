<?php
namespace App\Actions\Marketing;

use Carbon\Carbon;
use App\Jobs\SendSMSJob;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Marketing\SMS;
use App\Enum\Notification\PushNotificationEnum;
use App\Enum\Notification\PushNotificationForEnum;

class SMSAction
{
    protected $request;
    protected $input;
    function __construct(Request $request)
    {
        $this->request = $request;
        $this->input = $request->all();
        $this->input['created_by'] = auth()->user()->id;
        $this->input['slug'] = Str::slug($request->title.' '.rand(0000, 9999));
    }
    public function store()
    {
        $this->input['selection'] = json_encode($this->request->selection);
       
        $sMS = SMS::create($this->input);
        if($this->request->status == PushNotificationEnum::PUSHED){
            $this->input['pushed_date'] = Carbon::now();
            if($this->request->for == (int)  PushNotificationForEnum::ALL){
                dispatch(new SendSMSJob($sMS));
            }else{
                 
                dispatch(new SendSMSJob($sMS, $this->request->for, $this->request->selection));
            }
        }
    }
    public function update($id)
    {   
        $sMS = SMS::find($id);
        $sMS->update($this->input);
        if($this->request->status == PushNotificationEnum::PUSHED){
            $this->input['pushed_date'] = Carbon::now();
            if($this->request->for == (int)  PushNotificationForEnum::ALL){
                dispatch(new SendSMSJob($sMS));
            }else{
                dispatch(new SendSMSJob($sMS, $this->request->for, $this->request->selection));
            }
        }
    }
}