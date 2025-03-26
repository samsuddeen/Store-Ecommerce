<?php

namespace App\Http\Controllers;

use App\Models\RewardSection;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class RewardSectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rewardSection = RewardSection::paginate(20);
        return view("admin.RewardSection.index",compact("rewardSection"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         $rewardSection = new RewardSection();
        return view("admin.RewardSection.form",compact("rewardSection"));
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
            RewardSection::create($request->all());
            request()->session()->flash('success',"new RewardSection created successfully");
            DB::commit();
            return redirect()->route('RewardSection.index');
        } catch (\Throwable $th) {
            request()->session()->flash('error',$th->getMessage());
            DB::rollback();
            return redirect()->back()->withInput();

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\RewardSection  $rewardSection
     * @return \Illuminate\Http\Response
     */
    public function show(RewardSection $rewardSection)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\RewardSection  $rewardSection
     * @return \Illuminate\Http\Response
     */
    public function edit(RewardSection $rewardSection)
    {
        return view("admin.RewardSection.form",compact("rewardSection"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\RewardSection  $rewardSection
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RewardSection $rewardSection)
    {
         DB::beginTransaction();
          try{
           $rewardSection->update($request->all());
            request()->session()->flash('success',"new RewardSection created successfully");
            DB::commit();
            return redirect()->route('RewardSection.index');

        } catch (\Throwable $th) {
            request()->session()->flash('error',$th->getMessage());
            DB::rollback();
            return redirect()->back()->withInput();

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\RewardSection  $rewardSection
     * @return \Illuminate\Http\Response
     */
    public function destroy(RewardSection $rewardSection)
    {
        try{
             $rewardSection->delete();
              request()->session()->flash('success',"RewardSection deleted successfully");
            return redirect()->route('RewardSection.index');
        } catch (\Throwable $th) {
               request()->session()->flash('error',$th->getMessage());
            return redirect()->back()->withInput();
        }
    }
}
