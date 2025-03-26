<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class forgetpassword extends Mailable
{
    use Queueable, SerializesModels;

    protected $token;
    protected $collect;
    public function __construct($customer)
    {
        $this->customer = $customer;        
    }

    /**
     * Create a new message instance.
     *
     * @return void
     */

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {        
        return $this->view('sendmail', [
            'customer'=>$this->customer,
        ]);
    }
}
