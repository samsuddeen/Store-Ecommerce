<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use App\Models\Notification\PushNotification;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use App\Enum\Notification\PushNotificationForEnum;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class PushNotificationEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $psuhNotification;
    public $for;
    public $selection;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(PushNotification $psuhNotification, $for=null, $selection=0)
    {
        //
        $this->psuhNotification = $psuhNotification;
        $this->for = $for;
        $this->selection = $selection;
        info(json_encode($this->psuhNotification));
        info(json_encode($this->for));
        info(json_encode($this->selection));
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        if($this->for !== (int)  PushNotificationForEnum::ALL){
            info("All");
            return new Channel('push-notification-to-customer.'.$this->selection);
        }else{
            info("Not All");
            return new Channel('push-notification-to-customer.0');
        }
    }
}
