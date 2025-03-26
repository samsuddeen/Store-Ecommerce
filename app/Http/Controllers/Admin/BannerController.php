<?php

namespace App\Http\Controllers\Admin;

use App\Models\Banner;
use App\Http\Controllers\Controller;
use App\Http\Requests\BannerRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class BannerController extends Controller
{
    function __construct()
    {
        $this->middleware(['permission:banner-read'], ['only' => ['index']]);
        $this->middleware(['permission:banner-create'], ['only' => ['create', 'store']]);
        $this->middleware(['permission:banner-edit'], ['only' => ['edit', 'update']]);
        $this->middleware(['permission:banner-delete'], ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("admin.banner.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $banner = new Banner();
        return view("admin.banner.form",compact("banner"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BannerRequest $request)
    {
        DB::beginTransaction();
        $input = $request->all();
        $input['user_id']=$request->user()->id;
        try{
            Banner::create($input);
            session()->flash('success',"new Banner created successfully");
            DB::commit();
            return redirect()->route('banner.index');
        } catch (\Throwable $th) {
            session()->flash('error',$th->getMessage());
            DB::rollback();
            return redirect()->back()->withInput();

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function show(Banner $banner)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function edit(Banner $banner)
    {
        return view("admin.banner.form",compact("banner"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function update(BannerRequest $request, Banner $banner)
    {
         DB::beginTransaction();
         $input = $request->all();
         $input['user_id']=$request->user()->id;
          try{
            $banner->update($input);
            session()->flash('success',"new Banner created successfully");
            DB::commit();
            return redirect()->route('banner.index');

        } catch (\Throwable $th) {
            session()->flash('error',$th->getMessage());
            DB::rollback();
            return redirect()->back()->withInput();

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function destroy(Banner $banner)
    {
        try{
             $banner->delete();
              session()->flash('success',"Banner deleted successfully");
            return redirect()->route('banner.index');
        } catch (\Throwable $th) {
               session()->flash('error',$th->getMessage());
            return redirect()->back()->withInput();
        }
    }
}
