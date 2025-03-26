<?php

namespace App\Events;

use App\Models\Marketing\NewsLetter;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewsLetterEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $newsLetter;
    public $for;
    public $selection;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(NewsLetter $newsLetter, $for=1, $selection=[])
    {
        //
        info('event construction');
        $this->newsLetter = $newsLetter;
        $this->for = $for;
        $this->selection = $selection;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
