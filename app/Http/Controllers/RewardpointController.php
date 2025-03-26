<?php

namespace App\Http\Controllers;

use App\Models\RewardPoint;
use Illuminate\Http\Request;
use App\Models\RewardSection;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\RewardPointRequest;
use App\Http\Requests\RewardPointTableRequest;

class RewardpointController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $reward_section=null;
    protected $reward_point=null;
    public function __construct(RewardSection $reward_section,RewardPoint $reward_point)
    {
        $this->reward_section=$reward_section;
        $this->reward_point=$reward_point;
    }
    public function index()
    {
        $this->reward_section=$this->reward_section->get();
        
        return view('admin.reward.index')
        ->with('rewardsSections',$this->reward_section);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.reward.form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RewardPointRequest $request)
    {
        DB::beginTransaction();
        try{
            $data=$request->all();
            $this->reward_section->fill($data);
            $this->reward_section->save();
            DB::commit();
            $request->session()->flash('success','Created Successfully !!');
            return redirect()->route('rewardsection.index');
        }catch(\Throwable $th)
        {
            $request->session()->flash('error','Something Went Wrong !!');
            return redirect()->back();
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
        $this->reward_section=$this->reward_section->findOrFail($id);
        return view('admin.reward.form')
        ->with('rewardsection',$this->reward_section);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RewardPointRequest $request, $id)
    {
        $this->reward_section=$this->reward_section->findOrFail($id);
        DB::beginTransaction();
        try{
            $data=$request->all();
            $this->reward_section->fill($data);
            $this->reward_section->save();
            DB::commit();
            $request->session()->flash('success','Updated Successfully !!');
            return redirect()->route('rewardsection.index');
        }catch(\Throwable $th)
        {
            $request->session()->flash('error','Something Went Wrong !!');
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function rewardsetupForm()
    {
        $this->reward_point=$this->reward_point->first();

        return view('admin.reward.rewardsetup')
        ->with('data',$this->reward_point);
    }

    public function updateRewardsetupForm(RewardPointTableRequest $request,$id)
    {
        $this->reward_point=$this->reward_point->findOrFail($id);
        
        DB::beginTransaction();
        try{
            $data=$request->all();
            $this->reward_point->fill($data);
            $this->reward_point->save();
            DB::commit();
            $request->session()->flash('success','Updated Successfully !!');
            return redirect()->back();;
        }catch(\Throwable $th)
        {
            $request->session()->flash('error','Something Went Wrong !!');
            return redirect()->back();
        }
    }
}
