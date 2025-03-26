<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Order;
use App\Models\Task\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\ApiNotification;
use App\Models\Notification\TaskNotification  as TaskNotificationModel;
use App\Notifications\TaskNotification;

class DeliveryStaffController extends Controller
{   
    use ApiNotification;

    public function getAssignedTasks()
    {
        if(Auth::user()->hasRole('delivery'))
        {
            $data['tasks'] = Task::with('order.orderAssets.product','action')->whereHas('assigns',function($q){
                $q->where('assigned_to',Auth::user()->id);
            })->whereNotNull('order_id')->latest()->get();
    
            return response()->json(['status'=>200, 'error'=>false,'data'=>$data]);
        }elseif(Auth::user()->hasRole('staff')){
            $data['tasks'] = Task::with('order.orderAssets.product','action')->latest()->get();
            $data['staff_tasks'] = Task::whereHas('assigns',function($q){
                $q->where('assigned_to',Auth::user()->id);
            })->latest()->get();
            return response()->json(['status'=>200, 'error'=>false,'data'=>$data]);
        }else{
            return response()->json(['status'=>200, 'error'=>true, 'message'=>'You do not have permissions to perform this action']);
        }
    }

    public function updateTaskStatus(Request $request)
    {
        // if(Auth::user()->can('task-edit'))
        // {
            $task = Task::find($request->task_id);
            if($task)
            { 
                $task->status = $request->status;
                $task->save();
                if($task->order_id && $request->status == 'Completed')
                {
                    $order = Order::where('id',$task->order_id)->first();
                    $order->status = "5";
                    $order->save();
                }
                $notificationModel = TaskNotificationModel::create([
                    'type' => TaskNotificationModel::class,
                    'notifiable_type' => Task::class,
                    'notifiable_id' => $task->id,
                    'data' => json_encode([]),
                ]);
    
                $notification = new TaskNotification($notificationModel);
                $recipients = [];
                $recipients[] = Auth::user();
                $recipients[] = User::find(1);
                $recipients[] = User::find(2);
                $recipients[] = User::where('id',$task->assigned_by)->first();
    
                // Send notifications via API to the collected recipients
                foreach ($recipients as $recipient) {
                    $this->sendTaskNotification($task->title, $recipient->id, $task);
                    $recipient->notify($notification);
    
                }
    
                return response()->json(['status'=>200, 'error'=>false, 'data'=>$task ,'message'=>'Task updated successfully']);
            }else{
                return response()->json(['status'=>200, 'error'=>true, 'message'=>'Task could not be found']);
            }

        // }else{
        //     return response()->json(['status'=>200, 'error'=>true, 'message'=>'You do not have permissions to perform this action']);
        // }
    }

    public function fetchCurrentLocation(Request $request)
    {   
        $user_id = Auth::user()->id;
        // try{
            // if(!Auth::user()->hasRole('delivery'))
            // {
                $lat = $request->latitude ?? '85.312581';
                $long = $request->longitude ?? '27.700321';
                
                $accessToken = 'pk.eyJ1IjoiYWF5dXNobmlyYXVsYTk4IiwiYSI6ImNsa2o4Z2t2cDBpYWIzZnJlejJreW1rYm4ifQ.qWRAnChWFGe8ss6myoF_dQ';
                $url = "https://api.mapbox.com/geocoding/v5/mapbox.places/{$long},{$lat}.json?types=place&access_token={$accessToken}";
                
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $response = curl_exec($ch);
                curl_close($ch);
                    
                $data = json_decode($response, true);

                $cityName = '';
                if (!empty($data['features'])) {
                    $place = $data['features'][0];
                    $cityName = $place['text'];
                }   
                
                $users = User::where('address','LIKE','%'.$cityName.'%')->get();

                $response = [
                    'status' => 200, 
                    'error' => false,
                    'latitude' => $lat,
                    'longitude' => $long,
                    'users' => $users,
                ];

                return response()->json($response);
            // }
        // }catch(\Exception $e)
        // {
        //     return response()->json([
        //         'status' => 200, 
        //         'error' => true,
        //         'message' => $e->getMessage()
        //     ]);
            
        // }

    }

    public function getUserByLocation(Request $request)
    {
        $location = $request->location;
        $city = City::where('city_name',$location)->first();
        
        $users = User::where('address','LIKE','%'.$location.'%')->get();
        if($users->isNotEmpty())
        {
            return response()->json(['status'=>200, 'users'=>$users]);
        }else{
            return response()->json(['status'=>200, 'message'=> 'No user found in this location.']);
        }

    }
}
