<?php

namespace App\Listeners;

use App\Mail\OrderPlacedMail;
use App\Events\OrderPlacedEvent;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderPlacedListener
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
     * @param  object  $event
     * @return void
     */
    public function handle(OrderPlacedEvent $event)
    {
        Mail::to($event->user['email'])->send(new OrderPlacedMail($event->data,$event->user,'Order Placed'));
    }
}
