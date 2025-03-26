<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task\Action;

class TaskActionController extends Controller
{   
    public function __construct()
    {
        $this->middleware(['permission:task-action-read'], ['only' => ['index']]);
        $this->middleware(['permission:task-action-create'], ['only' => ['create', 'store']]);
        $this->middleware(['permission:task-action-edit'], ['only' => ['edit', 'update']]);
        $this->middleware(['permission:task-action-delete'], ['only' => ['destroy']]); 
   }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $actions = Action::latest()->get();
        return view('admin.task-mgmt.action.index', compact('actions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.task-mgmt.action.form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {           
        $this->validate($request, [
            'title' => 'required|unique:actions,title'
        ]);
        $is_default = $request->is_default == 'on' ? 1 : 0;

        $currentDefault = Action::where('is_default', 1)->first();
        if($currentDefault && $is_default == 1)
        {
            $currentDefault->update(['is_default' => 0]);
        }
        Action::create([
            'title' => $request->title,
            'status' => $request->status == 'on' ? true : false,
            'is_default' => $request->is_default == 'on' ? true : false
        ]);

        return redirect()->route('task-action.index')->with('success','Task action has been created successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $action = Action::find($id);
        return view('admin.task-mgmt.action.form',compact('action'));
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
        $action = Action::find($id);
        $is_default = $request->is_default == 'on' ? 1 : 0;

        if($action)
        {
            $currentDefault = Action::where('is_default', 1)->first();
            if($currentDefault && $is_default == 1)
            {
                $currentDefault->is_default = 0;
                $currentDefault->update(['is_default' => 0]);
            }

            $action->update([
                'title' => $request->title,
                'is_default' => $is_default,
                'status' => $request->status == 'on' ? true : false,
            ]);
        }
        return redirect()->route('task-action.index')->with('success','Task action has been created successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $action = Action::find($id);
        if($action)
        {   
            if($action->is_default == 1)
            {   
                $setDefault = Action::first();
                $setDefault->is_default = 1;
                $setDefault->save();
                $action->delete();
                return response()->json(['status'=>200, 'success'=>'Action deleted, New action set as default']);
            }
            $action->delete();
            return response()->json(['status'=>200, 'success'=>'Action deleted, New action set as default']);

        }
        return response()->json(['status'=>500, 'success'=>'Something went wrong!']);

        // return back()->with('error','Something went wrong');
    }
}
