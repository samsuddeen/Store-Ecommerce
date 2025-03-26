<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderConfirm extends Mailable
{
    use Queueable, SerializesModels;
    private $data,$info;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data,$info)
    {
        $this->data = $data;
        $this->info = $info;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Order Details')->markdown('frontend.emails.customer.customercheckoutmail')->with(['data'=>$this->data,'info'=>$this->info]);
    }
}
