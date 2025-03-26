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

class AdminOrderPlacedMail extends Mailable
{
    use Queueable, SerializesModels;
    protected $data;
    protected $user;
    protected $product;
    protected $msg;
    protected $setting;
    protected $menu;
    protected $social;
    protected $email_message;
    protected $province;
    protected $district;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data=null,$user=null,$msg)
    {   
        
        $this->data=$data;
        $this->user=$user;
        $this->msg=$msg;
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
       
        return $this->view('admin.message-setup.adminordermail')
        ->with('order',$this->data)
        ->with('msg',$this->msg)
        ->with('phone_num',$this->data->phone)
        ->with('ref_id',$this->data->ref_id)
        ->with('setting',$this->setting)
        ->with('social',$this->social)
        ->with('menu',$this->menu)
        ->with('email_message',$this->email_message)
        ->with('customer',$this->user)
        ->with('client', $this->user->name)??"";
    }

    public function getProductData($data)
    {
        return $productList=$data->orderAssets;
    }
}
