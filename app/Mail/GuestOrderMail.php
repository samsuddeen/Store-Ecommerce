<?php

namespace App\Mail;

use App\Models\Menu;
use App\Models\Setting;
use App\Models\EmailMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use App\Models\Setting\SocialSetting;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class GuestOrderMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    protected $data;
    protected $product;
    protected $setting;
    protected $menu;
    protected $social;
    protected $email_message;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data=null)
    {
        $this->data=$data;
        $this->product=$this->getProductData($this->data);
        $this->setting=Setting::get();
        $this->menu=Menu::get();
        $this->social=SocialSetting::orderBy('created_at', 'ASC')->where('status', 1)->get();
        $this->email_message=EmailMessage::first();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
       
        return $this->view('admin.message-setup.guestorder')
        ->with('data',$this->data)
        ->with('products',$this->product)
        ->with('msg','Your Order is Placed')
        ->with('phone_num',$this->data->phone)
        ->with('ref_id',$this->data->ref_id)
        ->with('setting',$this->setting)
        ->with('social',$this->social)
        ->with('menu',$this->menu)
        ->with('email_message',$this->email_message);
    }

    public function getProductData($data)
    {
        return $productList=$data->orderAssets;
    }
}
