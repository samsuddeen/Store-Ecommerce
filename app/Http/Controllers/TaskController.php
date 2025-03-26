<?php

namespace App\Http\Controllers;

use App\Actions\Notification\NotificationAction;
use App\Enum\Order\OrderStatusEnum;
use App\Helpers\EmailSetUp;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\Task\Task;
use App\Models\Task\Action;
use App\Models\User;
use App\Models\Product;
use App\Http\Requests\TaskRequest;
use App\Jobs\SendTaskNotification as JobsSendTaskNotification;
use App\Jobs\SendTaskNotificationJob;
use App\Models\Task\TaskAssign;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification\TaskNotification  as TaskNotificationModel;
use App\Models\Setting;
use App\Models\Task\TaskLog;
use App\Notifications\SendTaskNotification;
use App\Notifications\TaskNotification;
use App\Notifications\TaskReassignedNotification;
use App\Observers\TaskObserver;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\View;
use App\Traits\ApiNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use PDF;

class TaskController extends Controller
{   
    use ApiNotification;

    public function __construct()
    {
        $this->middleware(['permission:task-read'], ['only' => ['index']]);
        $this->middleware(['permission:task-create'], ['only' => ['create', 'store', 'addSubTask']]);
        $this->middleware(['permission:task-edit'], ['only' => ['edit']]);
        $this->middleware(['permission:task-delete'], ['only' => ['destroy']]); 
   }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   
        $data = $this->getFilteredTasks($request->input('filter_status'));
        $users = User::whereNotIn('id',[Auth::user()->id])
                    ->whereHas('roles',function($q){
                        return $q->whereIn('name',['staff','delivery']);
                    })->get();
        return view('admin.task-mgmt.task.index', compact('data','users'));
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        $actions = Action::where('status',1)->get();
        $users = User::with('roles')->where('status',1)
        ->whereHas('roles', function($q){
            return $q->whereIn('name',['staff','delivery']);
        })
        ->get();
        $orders = Order::latest()->where('status',4)->get();
        // dd($orders->product->name?);
        $products = Product::with('category')->where('publishStatus',1)
        ->whereHas('category',function($q){
            return $q->where('status',1)->latest();
        })
        ->latest()->get();
        return view('admin.task-mgmt.task.form',compact('actions','users','products', 'orders'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TaskRequest $request)
    {   

        $input = [
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status,
            'priority' => $request->priority,
            'start_date' => $request->start_date,
            'due_date' => $request->due_date,
            'action_id' => $request->action_id,
            'order_id' => $request->order_id ?? null,
            'product_id' => $request->product_id,
            'assigned_by' => Auth::user()->id,
            'created_by' => Auth::user()->id,
        ];
        try{
            DB::beginTransaction();

            $task = Task::create($input);
            $project_members = $request->members;
            if($project_members)
            {
                foreach($project_members as $key => $member)
                {
                    $taskAssign = TaskAssign::create([
                        'task_id' => $task->id,
                        'assigned_to' => $member
                    ]);

                }

            }else{
                $taskAssign = TaskAssign::create([
                    'task_id' => $task->id,
                    'assigned_to' => Auth::user()->id   
                ]);

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
            // Send notifications via web and collect recipients for API notifications
            foreach($project_members as $member)
            {   
                $user = User::where('id', $member)->first();
                $user->notify($notification);
                $recipients[] = $user;
            }
            // Send notifications via API to the collected recipients
            foreach ($recipients as $recipient) {
                $this->sendTaskNotification($task->title, $recipient->id, $task);
                $recipient->notify($notification);

            }
            // Dispatch the job to the queue
            // dispatch(new SendTaskNotificationJob($notificationModel));
            DB::commit();
            return redirect()->route('task.show',$task->id)->with('success','Task added successfully!');
        }catch(\Exception $e){
            DB::rollback();
            // session()->flash("error", $e->getMessage());
            // dd($e->getMessage());
            return back()->withErrors('error', $e->getMessage())->withInput();
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $task = Task::with('action','order','product','assignedBy','createdBy','updatedBy')->find($id);
        $selectedMembers = $task->assigns()->get();
        // dd($selectedMembers);
        return view('admin.task-mgmt.task.show',compact('task','selectedMembers'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Task $task)
    {   
        $actions = Action::where('status',1)->get();
        $users = User::with('roles')->where('status',1)
        ->whereHas('roles', function($q){
            return $q->whereIn('name',['staff','delivery']);
        })
        ->get();
        $orders = Order::latest()->where('status',4)->get();
        // dd($orders->product->name?);
        $products = Product::with('category')->where('publishStatus',1)
        ->whereHas('category',function($q){
            return $q->where('status',1)->latest();
        })
        ->latest()->get();
        $selectedMembers = $task->assigns()->pluck('assigned_to')->toArray();
        return view('admin.task-mgmt.task.form',compact('task','actions','users','orders','products','selectedMembers'));
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Task $task)
    {   
        if($request->ajax())
        {
            $task = Task::find($request->task_id);
            $task->status = $request->status;
            $task->updated_by = Auth::user()->id;

            if($task->order_id && $request->status == 'Completed')
            {
                $order = Order::where('id',$task->order_id)->first();
                $order->status = "5";
                $order->save();

                $notification_data = [
                        'from_model' => get_class(auth()->user()->getModel()),
                        'from_id' => auth()->user()->id,
                        'to_model' => get_class($order->user()->getModel()) ?? null,
                        'to_id' => $order->user->id,
                        'title' => 'You Order has been ' . 'Delivered',
                        'summary' => 'Please Show your order status',
                        'url' => route('Corder'),
                        'is_read' => false,
                    ];
                    
                EmailSetUp::OrderStatusMail(OrderStatusEnum::DELIVERED, $data = $order); 
                (new NotificationAction($notification_data))->store();
                }
             $task->save();


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

            return response()->json(['status'=>200]);
        }
        try{
            DB::beginTransaction();
            if($task)
            {
                $task->update([
                    'title' => $request->title,
                    'description' => $request->description,
                    'status' => $request->status,
                    'priority' => $request->priority,
                    'start_date' => $request->start_date,
                    'due_date' => $request->due_date,
                    'action_id' => $request->action_id,
                    'order_id' => $request->order_id,
                    'product_id' => $request->product_id,
                    'updated_by' => Auth::user()->id,
                ]);
                
                $project_members = $request->members;
                if($project_members)
                {
                    $task->assigns()->sync($project_members);
                }else{
                    $task->assigns()->sync(Auth::user()->id);
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
                // Send notifications via web and collect recipients for API notifications
                foreach($project_members as $member)
                {
                    $user = User::where('id', $member)->first();
                    $user->notify($notification);
                    $recipients[] = $user;
                }
    
                // Send notifications via API to the collected recipients
                foreach ($recipients as $recipient) {
                    $this->sendTaskNotification($task->title, $recipient->id, $task);
                    $recipient->notify($notification);
    
                }
                DB::commit();
                return redirect()->route('task.index')->with('success','Task updated successfully!');
           }else{
                return back()->with('error', 'Task not found');
           }
        }catch(\Exception $e)
        {   
            // dd($e->getMessage());
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        try{
            $task = Task::find($request->task_id);
            if($task)
            {   
                $task->deleted_by = Auth::user()->id;
                $task->save();
                $task->delete();
                return response()->json(['status'=>200, 'message'=>'Task Deleted successfully!']);
            }else{
                return response()->json(['status'=>404, 'message'=>'Task Not Found!']);
                
            }
        }catch(\Exception $e)
        {
            return back()->with('error',$e->getMessage());
        }
    }


    public function addSubTask(Request $request, $id)
    {   
        $request->validate([
            "title" => "required",
            "action_id" => "required"
        ]);
        $task = Task::find($id);
        $all_tasks = Task::latest()->get();
        $actions = Action::where('status',1)->get();
        $users = User::with('roles')->where('status',1)
        ->whereHas('roles', function($q){
            return $q->where("name","staff");
        })
        ->get();
        $selectedMembers = [];
        $selectedMembers = User::whereIn('id', old('members', []))->get();
        return view('admin.task-mgmt.sub-task.form',compact('task','all_tasks','actions','users','selectedMembers'));
    }

    public function downloadTaskPdf($id)
    {
        $task = Task::with('action','order','assigns','subTasks')->find($id);
        $setting=Setting::first()->value ?? null;
        View::share('task', $task);
        // return view('admin.task-mgmt.task.print',compact('task'));
        $pdf = PDF::loadView('admin.task-mgmt.task.print', compact('task','setting'));
        return $pdf->download($task->assigns->first()->name . '-' . $task->id  . '.pdf');
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
                

        return back()->with('success','Task reassigned successfully');

    }

    protected function getFilteredTasks($filter_status)
    {
        if ($filter_status != 'All') {
            $query=Task::where('status', $filter_status);
        }

        if (Auth::user()->hasRole('super admin') || Auth::user()->hasRole('staff')) {
            
            $query = Task::latest()->paginate(20);
        } else {
            $query = Task::whereHas('assigns', function ($q) {
                $q->where('assigned_to', Auth::user()->id);
            })->latest()->paginate(20);
        }
        return $query;
    }

    public function getTaskLogs()
    {
        $data['logs'] = TaskLog::with('task')->latest()->paginate(20);
        return view('admin.task-mgmt.logs',$data);
    }
    
}

