<?php

namespace App\Http\Controllers\Policy;

use App\Actions\Policy\PolicyAction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Policy\AppPolicy;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Data\Policy\ArrayPolicy\PolicyData;


class AppPolicyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $year = Carbon::now()->format('Y');
        $data['appPolicy'] = AppPolicy::where('year', $year)->first();
        return view("admin.policy.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $year = Carbon::now()->format('Y');
        $data = [];
        $appPolicy = AppPolicy::where('year', $year)->first();
        if($appPolicy){
            $data['appPolicy'] = $appPolicy;
            $data['assists'] = collect($appPolicy->assist)->groupBy('title');
        }
        $policies =(new PolicyData)->getData();
        $data['policies']  = $policies;
        return view("admin.policy.form", $data);
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
            AppPolicy::create($request->all());
            session()->flash('success',"new AppPolicy created successfully");
            DB::commit();
            return redirect()->route('app-policy.index');
        } catch (\Throwable $th) {
            session()->flash('error',$th->getMessage());
            DB::rollback();
            return redirect()->back()->withInput();

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Policy\AppPolicy  $appPolicy
     * @return \Illuminate\Http\Response
     */
    public function show(AppPolicy $appPolicy)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Policy\AppPolicy  $appPolicy
     * @return \Illuminate\Http\Response
     */
    public function edit(AppPolicy $appPolicy)
    {
        return view("admin.AppPolicy.form",compact("appPolicy"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Policy\AppPolicy  $appPolicy
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $appPolicy = AppPolicy::findOrFail($id);
        DB::beginTransaction();
          try{
            (new PolicyAction($request))->update($appPolicy);
            $appPolicy->update($request->all());
            session()->flash('success',"updated successfully");
            DB::commit();
            return redirect()->route('app-policy.index');
        } catch (\Throwable $th) {
            session()->flash('error',$th->getMessage());
            DB::rollback();
            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Policy\AppPolicy  $appPolicy
     * @return \Illuminate\Http\Response
     */
    public function destroy(AppPolicy $appPolicy)
    {
        try{
             $appPolicy->delete();
              session()->flash('success',"AppPolicy deleted successfully");
            return redirect()->route('AppPolicy.index');
        } catch (\Throwable $th) {
               session()->flash('error',$th->getMessage());
            return redirect()->back()->withInput();
        }
    }
}





