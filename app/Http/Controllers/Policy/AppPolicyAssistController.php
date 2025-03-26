<?php

namespace App\Http\Controllers\Policy;

use App\Models\Policy\Assist\AppPolicyAssist;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class AppPolicyAssistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $appPolicyAssist = AppPolicyAssist::paginate(20);
        return view("admin.AppPolicyAssist.index",compact("appPolicyAssist"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         $appPolicyAssist = new AppPolicyAssist();
        return view("admin.AppPolicyAssist.form",compact("appPolicyAssist"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try{
            AppPolicyAssist::create($request->all());
            request()->session()->flash('success',"new AppPolicyAssist created successfully");
            DB::commit();
            return redirect()->route('AppPolicyAssist.index');
        } catch (\Throwable $th) {
            request()->session()->flash('error',$th->getMessage());
            DB::rollback();
            return redirect()->back()->withInput();

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Policy\Assist\AppPolicyAssist  $appPolicyAssist
     * @return \Illuminate\Http\Response
     */
    public function show(AppPolicyAssist $appPolicyAssist)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Policy\Assist\AppPolicyAssist  $appPolicyAssist
     * @return \Illuminate\Http\Response
     */
    public function edit(AppPolicyAssist $appPolicyAssist)
    {
        return view("admin.AppPolicyAssist.form",compact("appPolicyAssist"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Policy\Assist\AppPolicyAssist  $appPolicyAssist
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AppPolicyAssist $appPolicyAssist)
    {
         DB::beginTransaction();
          try{
           $appPolicyAssist->update($request->all());
            request()->session()->flash('success',"new AppPolicyAssist created successfully");
            DB::commit();
            return redirect()->route('AppPolicyAssist.index');

        } catch (\Throwable $th) {
            request()->session()->flash('error',$th->getMessage());
            DB::rollback();
            return redirect()->back()->withInput();

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Policy\Assist\AppPolicyAssist  $appPolicyAssist
     * @return \Illuminate\Http\Response
     */
    public function destroy(AppPolicyAssist $appPolicyAssist)
    {
        try{
             $appPolicyAssist->delete();
              request()->session()->flash('success',"AppPolicyAssist deleted successfully");
            return redirect()->route('AppPolicyAssist.index');
        } catch (\Throwable $th) {
               request()->session()->flash('error',$th->getMessage());
            return redirect()->back()->withInput();
        }
    }
}
