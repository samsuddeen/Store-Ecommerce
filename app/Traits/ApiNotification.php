<?php

namespace App\Traits;

trait ApiNotification
{

    public function apiSendNotification($title,$type,$order)
    {
        $serverKey = 'AAAAgWp1FSE:APA91bG_CEoY14MKYBPeo_lhjvOYWplDAagVYbtYMXL0TQ-RPlTuTUtT2hYFum8QXQ24gkINU7YDCEI3hl3F2IaIZXeRbr0H9pjV9jImh5D1LD35R-4zXu0oCQZgDlZnD9HDo_yjLAT5';
        $notification = [
            'title' => 'Your Order is '.$title,
            'type'=>$type,
            'body'=>'Order Id '.(int)$order->id,
            'sound' => 'default',
            'icon'=>$order->orderAssets[0]->image ?? '',
            'is_personal'=>true,
            'image'=>$order->orderAssets[0]->image ?? ''
        ];
        $message = [
            'to' => '/topics/com.nectardigit.mystore'.$order->user_id,
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

    public function apiSendGeneralNotification($pushNotification)
    {
        $serverKey = 'AAAAgWp1FSE:APA91bG_CEoY14MKYBPeo_lhjvOYWplDAagVYbtYMXL0TQ-RPlTuTUtT2hYFum8QXQ24gkINU7YDCEI3hl3F2IaIZXeRbr0H9pjV9jImh5D1LD35R-4zXu0oCQZgDlZnD9HDo_yjLAT5';
        $notification = [
            'title' => $pushNotification->title,
            'type'=>'general',
            'body'=>(string)$pushNotification->description ?? '',
            'summary'=>$pushNotification->summary ?? '',
            'sound' => 'default',
            'icon'=>$pushNotification->image ?? '',
            'is_personal'=>false,
            'image'=>$pushNotification->image ?? ''
        ];
        $message = [
            // 'to' => '/topics/zhigu',
            'to' => '/topics/com.nectardigit.mystore',
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

    public function walletSendNotification($title,$type,$order)
    {
        $serverKey = 'AAAAgWp1FSE:APA91bG_CEoY14MKYBPeo_lhjvOYWplDAagVYbtYMXL0TQ-RPlTuTUtT2hYFum8QXQ24gkINU7YDCEI3hl3F2IaIZXeRbr0H9pjV9jImh5D1LD35R-4zXu0oCQZgDlZnD9HDo_yjLAT5';
        $notification = [
            'title' => 'Your order payment is success With '.$title.': Amount $.'.$order->total_price,
            'type'=>$type,
            'body'=>'Order Id '.(int)$order->id,
            'sound' => 'default',
            'icon'=>$order->orderAssets[0]->image ?? '',
            'is_personal'=>true,
            'image'=>$order->orderAssets[0]->image ?? ''
        ];
        $message = [
            // 'to' => '/topics/zhigu',
            'to' => '/topics/'.$order->user_id,
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

    public function sendTaskNotification($title, $recipientId, $task)
    {
        $serverKey = 'AAAAgWp1FSE:APA91bG_CEoY14MKYBPeo_lhjvOYWplDAagVYbtYMXL0TQ-RPlTuTUtT2hYFum8QXQ24gkINU7YDCEI3hl3F2IaIZXeRbr0H9pjV9jImh5D1LD35R-4zXu0oCQZgDlZnD9HDo_yjLAT5';
    
        $notification = [
            'title' => 'A new task has been assigned to you. Task Name: ' . $title,
            'type' => 'task',
            'body' => $task->title,
            'sound' => 'default',
            'icon' => '',
            'is_personal' => true,
            'image' => '',
        ];
    
        $message = [
            'to' => '/topics/'.$recipientId,
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
    
        if ($error) {
            // Handle error
            \Log::error('FCM notification error: ' . $error);
            return response()->json(['status' => 401, 'message' => 'Failed to send FCM notification']);
        } else {
            // Notification sent successfully
            \Log::info('FCM notification response: ' . $response);
            return response()->json(['status' => 200, 'message' => 'FCM notification sent successfully']);
        }
    }
    
}
