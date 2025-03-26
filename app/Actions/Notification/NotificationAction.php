<?php
namespace App\Actions\Notification;

use Illuminate\Http\Request;
use App\Providers\NotificationCreated;
use App\Models\Notification\Notification;

class NotificationAction
{
    protected $data;
    protected $channel;
    function __construct($data, $channel=null)
    {
        $this->data = $data;
        $this->channel = $channel;
    }
    public function store()
    {
        $notification = Notification::create($this->data);
        NotificationCreated::dispatch($notification->toArray(), $this->channel);
    }


    private function getMessage()
    {
        
    }
}