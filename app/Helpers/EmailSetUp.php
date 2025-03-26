<?php

namespace App\Helpers;

use App\Mail\DefaultMail;
use App\Mail\CheckoutMail;
use App\Mail\DeliveredMail;
use App\Mail\GuestOrderMail;
use App\Mail\FundRequestMail;
use App\Mail\OrderPlacedMail;
use App\Mail\OrderStatusMail;
use App\Mail\RefundStatusMail;
use App\Actions\Mail\MailSetup;
use App\Mail\PayoutRequestMail;
use App\Events\OrderPlacedEvent;
use App\Mail\OrderStatusChangeMail;
use Illuminate\Support\Facades\Mail;
use App\Mail\PayoutRequestStatusMail;
use App\Enum\MessageSetup\MessageSetupEnum;
use App\Mail\AdminOrderPlacedMail;

class EmailSetUp
{

    public static function sendMail($enum_value = null, $data = null, $user = null)
    {
        $setting = \App\Models\Setting::where('key','email')->first();
       
                switch ($enum_value) {
            case 1:
              
                $filters = [
                    'title' => $enum_value,
                ];
                (new MailSetup($filters))->setToFile();
               
                Mail::to($user->email)->send(new OrderPlacedMail($data, $user,'Order Placed'));
                // if($setting->value){
                 
                    Mail::to($setting->value)->send(new AdminOrderPlacedMail($data, $user,'Order Placed'));
                // }
                break;
            case 2:
                $filters = [
                    'title' => $enum_value,
                ];
                (new MailSetup($filters))->setToFile();
                Mail::to($user->email)->send(new CheckoutMail($data, $user));
                break;
            case 3:
                $filters = [
                    'title' => $enum_value,
                ];

                (new MailSetup($filters))->setToFile();

                Mail::to($user->email)->send(new DeliveredMail($data, $user));
                break;
            case 4:
                $filters = [
                    'title' => $enum_value,
                ];

                (new MailSetup($filters))->setToFile();

                Mail::to($user->email)->send(new OrderStatusMail($data, $user));
                break;
            case 5:
                $filters = [
                    'title' => $enum_value,
                ];
                (new MailSetup($filters))->setToFile();

                Mail::to($user->email)->send(new PayoutRequestMail($data = null, $user));
                break;
            case 6:

                $filters = [
                    'title' => $enum_value,
                ];
                (new MailSetup($filters))->setToFile();

                Mail::to($user->email)->send(new PayoutRequestStatusMail($data = null, $user));
                break;
            case 7:
                $filters = [
                    'title' => $enum_value,
                ];
                (new MailSetup($filters))->setToFile();

                Mail::to($user->email)->send(new FundRequestMail($data = null, $user));
                break;
            case 8:
                $filters = [
                    'title' => $enum_value,
                ];
                (new MailSetup($filters))->setToFile();

                Mail::to($user->email)->send(new RefundStatusMail($data = null, $user));
                break;
            default:
                Mail::to($user->email)->send(new DefaultMail($data = null, $user));
                break;
        }
    }

    public static function guestOrderMail($enum_value = null,$data,$email)
    {
        $filters = [
            'title' => $enum_value,
        ];
        (new MailSetup($filters))->setToFile();

        Mail::to($email)->send(new GuestOrderMail($data));
    }

    public static function OrderStatusMail($messageStatus,$order)
    {
        
        switch($messageStatus)
        {
           
            case 'ready_to_ship':
                
                $filters = [
                    'title' =>MessageSetupEnum::ORDER_READY_TO_SHIP ,
                ];
                (new MailSetup($filters))->setToFile();
                
                Mail::to($order->email)->send(new OrderStatusChangeMail($order,'Ready To Ship'));
                break;
            case 'dispatched':
                $filters = [
                    'title' =>MessageSetupEnum::DISPATCHED ,
                ];
                (new MailSetup($filters))->setToFile();
                Mail::to($order->email)->send(new OrderStatusChangeMail($order,'Dispatched'));
                break;
            case 'shiped':
                $filters = [
                    'title' =>MessageSetupEnum::SHIPED ,
                ];
                (new MailSetup($filters))->setToFile();
                Mail::to($order->email)->send(new OrderStatusChangeMail($order,'Shipped'));
                break;
            case 'delivered':
                $filters = [
                    'title' =>MessageSetupEnum::DELIVERED ,
                ];
                (new MailSetup($filters))->setToFile();
                Mail::to($order->email)->send(new OrderStatusChangeMail($order,'Delivered'));
                break;
            case 'cancel':
                $filters = [
                    'title' =>MessageSetupEnum::CANCEL ,
                ];
                (new MailSetup($filters))->setToFile();
                Mail::to($order->email)->send(new OrderStatusChangeMail($order,'Cancel'));
                break;
            case 'reject':
                $filters = [
                    'title' =>MessageSetupEnum::REJECTED ,
                ];
                (new MailSetup($filters))->setToFile();
                Mail::to($order->email)->send(new OrderStatusChangeMail($order,'Rejected'));
                break;

        }
    }
}
