<?php

namespace App\Providers;

use App\Providers\NotificationCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendNotificationToModel
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Providers\NotificationCreated  $event
     * @return void
     */
    public function handle(NotificationCreated $event)
    {
        //
    }
}
