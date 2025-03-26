<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendSubscriberMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->sendMailtoSubscriber($this->data);
        $this->sendMailtoAdmin($this->data);
    }

    public function sendMailtoSubscriber($data)
    {
        Mail::send('email.subscriber.customer', $data, function ($message) use ($data) {
            $message->subject('Thanks for subscribing us.');
            $message->from($data['admin_email']);
            $message->to($data['email']);
        });
    }

    public function sendMailtoAdmin($data)
    {
        Mail::send('email.subscriber.admin', $data, function ($message) use ($data) {
            $message->subject('Thanks for subscribing us.');
            $message->from($data['email']);
            $message->to($data['admin_email']);
        });
    }
}
