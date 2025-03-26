<?php

namespace App\Providers;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotificationCreated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $data;
    public $channel="to-customer";
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($data, $channel=null)
    {
        //
        $this->data = $data;
        if($channel !==null){
            $this->channel = $channel;
        }
        info("Event Construct");
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        info("Boardcat on channel");
        if($this->channel !== "to-backend"){
            return new Channel($this->channel.'.'.$this->data['to_id']);
        }else{
            return new Channel($this->channel);
        }
    }
}
