<?php

namespace App\Http\Controllers\Api;

use App\Models\Menu;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Setting\SocialSetting;

class MenuController extends Controller
{
    public function getMenuData()
    {
        $menu=Menu::where('status',1)->get();
        $response=[
            'error'=>false,
            'data'=>$menu,
            'msg'=>'Menu Content Data'
        ];
        return response()->json($response,200);
    }

    public function getSocialSite()
    {
        $social=SocialSetting::where('status',1)->get();
        $item1=[];
        foreach($social as $data)
        {   
            $item1[strtolower($data->title)]=$data->url;
        }
        $setting=Setting::get();
        $item1['name']=$setting[1]->value;
        $item1['address']=$setting[7]->value;
        $item1['phone']=$setting[2]->value;
        $item1['email']=$setting[3]->value;
        $response=[
            'error'=>false,
            'data'=>$item1,
            'msg'=>'Social Content Data'
        ];
        return response()->json($response,200);
    }

    public function testNotify()
    {
        $serverKey = 'AAAAgWp1FSE:APA91bG_CEoY14MKYBPeo_lhjvOYWplDAagVYbtYMXL0TQ-RPlTuTUtT2hYFum8QXQ24gkINU7YDCEI3hl3F2IaIZXeRbr0H9pjV9jImh5D1LD35R-4zXu0oCQZgDlZnD9HDo_yjLAT5';
        $notification = [
            'title' => 'Test Notify',
            'type'=>'Order',
            'status'=>'Delivered',
            'sound' => 'default',
        ];
        $message = [
            'to' => '/topics/' . 129,
            'notification' => $notification,
        ];
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://fcm.googleapis.com/fcm/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($message),
            CURLOPT_HTTPHEADER => [
                'Authorization: key=' . $serverKey,
                'Content-Type: application/json',
            ],
        ]);
        $response = curl_exec($curl);
        $error = curl_error($curl);
        curl_close($curl);
    }
}
