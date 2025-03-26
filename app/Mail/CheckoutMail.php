<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CheckoutMail extends Mailable
{
    use Queueable, SerializesModels;
    protected $data;
    protected $user;
    protected $product;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data=null,$user=null)
    {
        $this->data=$data;
        $this->user=$user;
        $this->product=$this->getProductData($this->data);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('admin.message-setup.checkoutmail')
        ->with('data',$this->data)
        ->with('client',$this->user->name)
        ->with('products',$this->product);
    }

    public function getProductData($data)
    {
        
        return $productList=$data->orderAssets;
    }
}
