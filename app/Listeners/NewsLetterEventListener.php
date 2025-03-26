<?php

namespace App\Listeners;

use App\Events\NewsLetterEvent;
use App\Jobs\NewsLetterSendJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NewsLetterEventListener
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
     * @param  \App\Events\NewsLetterEvent  $event
     * @return void
     */
    public function handle(NewsLetterEvent $event)
    {
        //
        info('dispatchinng the data');
        dispatch(new NewsLetterSendJob($event->newsLetter, $event->for, $event->selection));
    }
}
