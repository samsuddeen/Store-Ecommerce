<?php
namespace App\Actions\Notification;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Traits\ApiNotification;
use App\Events\PushNotificationEvent;
use App\Models\Notification\PushNotification;
use App\Enum\Notification\PushNotificationEnum;
use App\Enum\Notification\PushNotificationForEnum;

class PushNotificationAction
{
    use ApiNotification;
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
        $pushNotification = PushNotification::create($this->input);
        if($pushNotification->status==1)
        {
            $this->apiSendGeneralNotification($pushNotification);
        }
       
        if($this->request->status == PushNotificationEnum::PUSHED){
            $this->input['pushed_date'] = Carbon::now();
            if($this->request->for == (int)  PushNotificationForEnum::ALL){
                PushNotificationEvent::dispatch($pushNotification, $this->request->for);
            }else{
                foreach($this->request->selection as $selected_id){
                    PushNotificationEvent::dispatch($pushNotification, $this->request->for, $selected_id);
                }
            }
        }
    }
    public function update(PushNotification $pushNotification)
    {
        $pushNotification->update($this->input);
        if($pushNotification->status==1)
        {
            $this->apiSendGeneralNotification($pushNotification);
        }
        if($this->request->status == PushNotificationEnum::PUSHED){
            $this->input['pushed_date'] = Carbon::now();
            if($this->request->for == (int)  PushNotificationForEnum::ALL){
                PushNotificationEvent::dispatch($pushNotification, $this->request->for);
            }else{
                foreach($this->request->selection as $selected_id){
                    PushNotificationEvent::dispatch($pushNotification, $this->request->for, $selected_id);
                }
            }
        }
    }
}