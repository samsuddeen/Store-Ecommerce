<?php

namespace App\Mail;

use App\Models\Menu;
use App\Models\User;
use App\Models\Order;
use App\Models\Setting;
use App\Models\EmailMessage;
use App\Models\New_Customer;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use App\Models\Setting\SocialSetting;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderStatusChangeMail extends Mailable
{
    use Queueable, SerializesModels;
    protected $order;
    protected $msg;
    protected $setting;
    protected $menu;
    protected $social;
    protected $email_message;
    protected $user;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($order,$msg)
    {
        
        $this->order=$order;
        $this->msg=$msg;
        $this->setting=Setting::get();
        $this->menu=Menu::get();
        $this->social=SocialSetting::orderBy('created_at', 'ASC')->where('status', 1)->get();
        $this->email_message=EmailMessage::first();
        $this->user=New_Customer::where('id',$this->order->user_id)->first() ?? '';
       
        
        
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        
        return $this->view('frontend.emails.customer.newmail')
        ->with('order',$this->order)
        ->with('msg',$this->msg)
        ->with('phone_num',$this->user->phone)
        ->with('setting',$this->setting)
        ->with('social',$this->social)
        ->with('menu',$this->menu)
        ->with('email_message',$this->email_message)
        ->with('client',$this->user->name ?? '');
    }
}
