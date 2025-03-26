<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task\Task;
use App\Datatables\TaskDatatables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Task\TaskAction;
use App\Models\Task\TaskAssign;
use App\Models\Notification\TaskNotification  as TaskNotificationModel;
use App\Models\Order;
use App\Models\Task\Action;
use App\Models\Task\TaskLog;
use App\Notifications\TaskNotification;
use Illuminate\Support\Facades\Notification;
use App\Models\User;
use App\Notifications\TaskReassignedNotification;
use Illuminate\Support\Facades\Validator;
use App\Traits\ApiNotification;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Http;

class TaskController extends Controller
{       
    use ApiNotification;

    public function index()
    {
        // if(Auth::user()->can('task-read'))
        // {
            if(Auth::user()->hasRole('super admin'))
            {
                $data = Task::with('assigns')->latest()->paginate(10);
            }else{
                $data = Task::with('assigns')->whereHas('assigns', function($q){
                    $q->where('assigned_to', Auth::user()->id);
                })
                ->latest()->paginate(10);
            }
            $response = [
                'status' => 200,
                'error' => false,
                'data' => $data,
                'message' => 'All tasks'
            ];
    
            return response()->json($response);
        // }else{
        //     $response = [
        //         'status' => 403,
        //         'error' => true,
        //         'message' => 'You are not allowed to read task'
        //     ];
        // }

    }

    public function getTaskActions()
    {   
        // if(Auth::user()->can('task-action-read')){
            $actions = Action::select('id','title')->where('status',1)->get();
            return response()->json(['status'=>200, 'error'=>false, 'data'=>$actions,'message'=>'Task Actions']);
        // }else{
        //     return response()->json(['status'=>403, 'error'=>true,'message'=>'You are not allowed to view task actions']);
        // }

    }

    public function getUsers()
    {   
        // if(Auth::user()->can('user-read'))
        // {
            $users = User::with('roles')->role(['staff','delivery'])->select('id','name')->where('status',1)->get();
            return response()->json(['status'=>200, 'error'=>false, 'data'=>$users,'message'=>'User List']);
        // }else{
        //     return response()->json(['status'=>403, 'error'=>true,'message'=>'You are not allowed to view users']);
        // }

    }

    public function getOrders()
    {   
        // if(Auth::user()->can('order-read'))
        // {
            $orders = Order::select('id','ref_id')->where('status',4)->latest()->get();
            $response = [
                'status' => 200,
                'error' => false,
                'data' => $orders,
                'message' => 'Order List'
            ];
    
            return response()->json($response);
        // }else{
        //     return response()->json(['status'=>403, 'error'=>true,'message'=>'You are not allowed to view orders']);
        // }

    }

    public function getStatusPriority()
    {
        $data['status'] = [
            'Assigned',
            'Pending',
            'In-Progress',
            'Completed',
            'Cancelled',
            'On Hold',
            'Overdue',
        ];

        $data['priority'] = [
            'None',            
            'Low',            
            'Medium',            
            'High',            
            'Urgent',            
            'Emergency',            
        ];

        $data['actions'] = Action::select('id','title')->where('status',1)->get();

        $data['orders'] = Order::select('id','ref_id')->where('status',4)->latest()->get();

        return response()->json(['status'=>200, 'error'=>false, 'data'=>$data, 'message'=>'task data']);
    }

    public function store(Request $request)
    {   
        // if(Auth::user()->can('task-create'))
        // {
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'action_id' => 'required',
                'order_id' => 'nullable'
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $input = [
                'title' => $request->title,
                'description' => $request->description,
                'status' => $request->status,
                'priority' => $request->priority,
                'start_date' => $request->start_date,
                'due_date' => $request->due_date,
                'action_id' => $request->action_id,
                'order_id' => $request->order_id ?? null,
                'product_id' => $request->product_id ?? null,
                'assigned_by' => Auth::user()->id ?? 1,
                'created_by' => Auth::user()->id ?? 1,
            ];
            try{
                DB::beginTransaction();
                $task = Task::create($input);
                $project_members = $request->assigned_to;
                if (is_array($project_members)) {
                    foreach ($project_members as $key => $member) {
                        $taskAssign = TaskAssign::create([
                            'task_id' => $task->id,
                            'assigned_to' => $member
                        ]);
                    }
                } else {
                    $taskAssign = TaskAssign::create([
                        'task_id' => $task->id,
                        'assigned_to' => $project_members
                    ]);
                   
                    $notificationModel = TaskNotificationModel::create([
                        'type' => TaskNotificationModel::class,
                        'notifiable_type' => Task::class,
                        'notifiable_id' => $task->id,
                        'data' => json_encode([]),
                    ]);
            
                    // Send email notification for single user
                    $user = User::find($project_members);
                    Notification::route('mail', $user->email)->notify(new TaskNotification($notificationModel));
                }
                $this->sendTaskNotification($task->title, $project_members ?? 1, $task);
    
                DB::commit();
                if($this->sendTaskNotification($task->title, $project_members ?? 1, $task)){
                    return response()->json(['status'=>201, 'message'=>'Task added successfully', 'data'=>$task, 'notify'=>'Notification sent successfully!']);
                }
                return response()->json(['status'=>201, 'message'=>'Task added successfully', 'data'=>$task]);
    
            }catch(\Exception $e){
                DB::rollback();
                // dd($e->getMessage());
                return response()->json(['status'=>401, 'message'=>$e->getMessage()]);
            }
        // }else{
        //     return response()->json(['status'=>403, 'error'=>true,'message'=>'You are not allowed to create tasks']);
        // }
    }


    public function update(Request $request, $id)
    {   
        $task = Task::find($id);
        if($task)
        {   
            // try{
                $input = [
                    'title' => $request->title ?? $task->title,
                    'description' => $request->description ?? $task->description,
                    'status' => $request->status ?? $task->status,
                    'start_date' => $request->start_date ?? $task->start_date, 
                    'due_date' => $request->due_date ?? $task->due_date,
                    'action_id' => $request->action_id ?? $task->action_id,
                    'order_id' => $request->order_id ?? $task->order_id,
                    'product_id' => $request->product_id ?? $task->product_id, 
                    'priority' => $request->priority ?? $task->priority, 
                    'updated_by' => Auth::user()->id
                ];
                // dd($task, $input);
                $task->update($input);
                $project_members = $request->assigned_to;
                if($project_members)
                {   
                    $task->assigns()->sync($project_members);
                }else{
                    $task->assigns()->sync(Auth::user()->id);
                }
                $this->sendTaskNotification($task->title, $project_members ?? 1, $task);

                DB::commit();
                if($this->sendTaskNotification($task->title, $project_members ?? 1, $task)){
                    return response()->json(['status'=>201, 'message'=>'Task added successfully', 'data'=>$task, 'notify'=>'Notification sent successfully!']);
                }
                return response()->json(['status'=>201, 'message'=>'Task added successfully', 'data'=>$task]);

            // }catch(Exception $e)
            // {
            //     return response()->json(['status'=>200 ,'error'=>true, 'message'=>$e->getMessage()]);
            // }
        }else{
            return response()->json(['status'=>404, 'error'=>true, 'message'=>'Task not found']);
        }
        // if(Auth::user()->can('task-update'))
        // {
        //     try{
        //         DB::beginTransaction();
        //         if($task)
        //         {   
        //             $task->update([
        //                 'title' => $request->title ?? $task->title,
        //                 'description' => $request->description ?? $task->description,
        //                 'status' => $request->status ?? $task->status,
        //                 'priority' => $request->priority ?? $task->priority,
        //                 'start_date' => $request->start_date ?? $task->start_date,
        //                 'due_date' => $request->due_date ?? $task->due_date,
        //                 'action_id' => $request->action_id ?? $task->action_id,
        //                 'order_id' => $request->order_id ?? $task->order_id,
        //                 'product_id' => $request->product_id ?? $task->product_id,
        //                 'updated_by' => Auth::user()->id ?? $task->updated_by,
        //             ]);
        
        //             $project_members = $request->members;
        //             if($project_members)
        //             {
        //                 $task->assigns()->sync($project_members);
        //             }else{
        //                 $task->assigns()->sync(Auth::user()->id);
        //             }
    
        //             DB::commit();
        //             $this->sendTaskNotification($task->title, Auth::user()->id ?? 1, $task);
        //             return response()->json(['status'=>200, 'error'=>false,'data'=>$task,'message'=>'Task updated successfully']);
        //        }else{
        //             return back()->with('error', 'Task not found');
        //        }
        //     }catch(\Exception $e)
        //     {   
        //         // dd($e->getMessage());
        //         return response()->json(['status'=>200, 'error'=>true,'message'=>$e->getMessage()]);
        //     }
        // }else{
        //     return response()->json(['status'=>403, 'error'=>false, 'message'=>'You are not allowed to update tasks']);
        // }
    }

    public function destroy($id)
    {
        if(Auth::user()->can('task-delete'))
        {
            $task = Task::find($id);
            $task->delete();
            return response()->json(['status'=>200, 'error'=>false, 'message'=>'Task deleted successfully']);
        }else{
            return response()->json(['status'=>403, 'error'=>true, 'message'=>'You are not allowed to delete task']);
        }
    }
    
    public function reassignTask(Request $request)
    {
        $task = Task::find($request->task_id);
        
        $task->reassigned_by = Auth::user()->id;
        $task->reassign_date_time = Carbon::now()->format('Y-m-d H:i:s');
        $task->priority = $task->priority;
        $task->save();

        $reason = $request->reason;
        if($request->assigned_to)
        {
            $task_id = $task->id;
            $assigned_to = $request->assigned_to;

            TaskAssign::create([
                'task_id' => $task_id,
                'assigned_to' => $assigned_to
            ]);
        }


        TaskLog::create([
            'task_id' => $task->id,
            'reassigned_by' => $task->reassigned_by,
            'assigned_to' => $assigned_to,
            'reason' => $reason
        ]);

        $notification = new TaskReassignedNotification($task, $reason);

        $recipients[] = Auth::user();
        $recipients[] = User::find(1);
        $recipients[] = User::find(2);
        $recipients[] = User::find($assigned_to);


        Notification::send($recipients, $notification);

        $deviceTokens = [];

        $notificationData = [
            'title' => 'Task Reassigned',
            'body' => 'The task has been reassigned. Reason: ' . $reason,
        ];

        $response = Http::withHeaders([
            'Authorization' => 'key=AAAAmfXJoYU:APA91bFAnrKYZFfn1XlPswoQOMW51nrZ1PjXz0fDBjGmQDfV4u21lWrQdV5-PbZrL5IQoBAz8lNpisi4xm00YVeQaAo5xAan37MKeQLh0mBe8tREJFCTH-E_YuuHms5Tvmzrv5VaJZCM',
            'Content-Type' => 'application/json',
        ])->post('https://fcm.googleapis.com/fcm/send', [
            'registration_ids' => $deviceTokens,
            'notification' => $notificationData,
        ]);
                

        $response = [
            'status' => 200, 
            'error' => false,
            'data' => $task,
            'msg' => 'Task Reassigned Successfully'
        ];

    }

}
