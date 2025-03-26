<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task\SubTask;
use App\Models\Task\Action;
use App\Models\Task\SubTaskAssign;
use App\Models\User;
use App\Models\Task\Task;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SubTaskController extends Controller
{   
    public function __construct()
    {
        $this->middleware(['permission:task-read'], ['only' => ['index']]);
        $this->middleware(['permission:task-create'], ['only' => ['create', 'store']]);
        $this->middleware(['permission:task-edit'], ['only' => ['edit', 'update']]);
        $this->middleware(['permission:task-delete'], ['only' => ['destroy']]); 
   }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        if(Auth::user()->hasRole('super admin'))
        {
            $data = SubTask::latest()->get();
        }else{
            $data = SubTask::whereIn('id', function ($query) {
                $query->select('subtask_id')
                    ->from('sub_task_assigns')
                    ->where('assigned_to', Auth::user()->id);
            })
            ->orWhere('created_by', Auth::user()->id)
            ->orWhere('assigned_by', Auth::user()->id)
            ->latest()
            ->get();
        }
        // $data = SubTask::latest()->get();
        return view('admin.task-mgmt.sub-task.index',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        $all_tasks = Task::latest()->get();
        $actions = Action::where('status',1)->get();
        $users = User::with('roles')->where('status',1)
        ->whereHas('roles', function($q){
            return $q->where("name","staff");
        })
        ->get();
        $selectedMembers = [];
        $selectedMembers = User::whereIn('id', old('members', []))->get();

        return view('admin.task-mgmt.sub-task.form',compact('all_tasks','actions','users','selectedMembers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            "action_id" => "required"
        ]);
        $input = [
            'task_id' => $request->task_id,
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status,
            'priority' => $request->priority,
            'start_date' => $request->start_date,
            'due_date' => $request->due_date,
            'action_id' => $request->action_id,
            'assigned_by' => Auth::user()->id,
            'created_by' => Auth::user()->id,
        ];
        try{
            DB::beginTransaction();

            $subtask = SubTask::create($input);
            $project_members = $request->members;
            if($project_members)
            {
                foreach($project_members as $key => $member)
                {
                    SubTaskAssign::create([
                        'subtask_id' => $subtask->id,
                        'assigned_to' => $member
                    ]);
                }
            }else{
                SubTaskAssign::create([
                    'subtask_id' => $subtask->id,
                    'assigned_to' => Auth::user()->id
                ]);
            }
           
            DB::commit();
            return redirect()->route('subtask.show',$subtask->id)->with('success','Subtask added successfully!');
        }catch(\Exception $e){
            DB::rollback();
            // dd($e->getMessage());
            return back()->with('error', $e->getMessage());
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
        $subtask = SubTask::with('createdBy')->find($id);
        $selectedMembers = $subtask->assigns()->get();
        // dd($selectedMembers);
        return view('admin.task-mgmt.sub-task.show',compact('subtask','selectedMembers'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {   
        $subtask = SubTask::find($id);
        $all_tasks = Task::latest()->get();
        $actions = Action::where('status',1)->get();
        $users = User::with('roles')->where('status',1)
        ->whereHas('roles', function($q){
            return $q->where("name","staff");
        })
        ->get();
        $selectedMembers = $subtask->assigns()->get();
        return view('admin.task-mgmt.sub-task.form',compact('subtask','actions','users','all_tasks','selectedMembers'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {   
        if($request->ajax())
        {
            $subtask = SubTask::find($request->task_id);
            $subtask->status = $request->status;
            $subtask->updated_by = Auth::user()->id;
            $subtask->save();

            return response()->json(['status'=>200]);
        }
        $subtask = SubTask::find($id);
        $input = [
            'task_id' => $request->task_id,
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status,
            'priority' => $request->priority,
            'start_date' => $request->start_date,
            'due_date' => $request->due_date,
            'action_id' => $request->action_id,
            'updated_by' => Auth::user()->id,
        ];
        
        if($subtask)
        {
            try{
                DB::beginTransaction();
                $subtask->update($input);

                $project_members = $request->members;
                if($project_members)
                {
                    $subtask->assigns()->sync($project_members);
                }else{
                    $subtask->assigns()->sync(Auth::user()->id);
                }

                DB::commit();
                return redirect()->route('subtask.show',$subtask->id)->with('success','Task updated successfully!');

            }catch(\Exception $e){
                // dd($e->getMessage());
                return back()->with('error',$e->getMessage());
            }
        }else{
            return back()->with('error','Subtask could not be found');
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
            $task = SubTask::find($request->task_id);
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
}
