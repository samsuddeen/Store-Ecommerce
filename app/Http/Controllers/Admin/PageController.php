<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PageRequest;
use App\Http\Requests\PageUpdateRequest;
use Illuminate\Http\Request;
use App\Models\Page;
use Illuminate\Support\Facades\DB;

class PageController extends Controller
{
    protected $page=null;
    public function __construct(Page $page)
    {
        $this->page=$page;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.page.index')
        ->with('pages',$this->page->get())
        ->with('n',1);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $page = new Page();
        return view("admin.page.form",compact("page"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PageRequest $request)
    {

        DB::beginTransaction();
        $input = $request->all();
        $input['user_id']=$request->user()->id;
        $input['slug']=$this->page->getSlug($request->title);
        try{
            $this->page->fill($input);
            $this->page->save();

            session()->flash('success',"New Page created successfully");
            DB::commit();
            return redirect()->route('page.index');

        } catch (\Throwable $th) {
            session()->flash('error',$th->getMessage());
            DB::rollback();
            return redirect()->back()->withInput();

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
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Page $page)
    {
        return view('admin.page.form')
        ->with('page',$page);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PageUpdateRequest $request,Page $page)
    {

        DB::beginTransaction();
        $input = $request->all();
        try{
            $page->fill($input);
            $page->save();

            session()->flash('success',"Page Updated successfully");
            DB::commit();
            return redirect()->route('page.index');

        } catch (\Throwable $th) {
            session()->flash('error',$th->getMessage());
            DB::rollback();
            return redirect()->back()->withInput();

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Page $page,$pages)
    {
    //     try{
    //         Page::where('id',$pages)->delete();
    //          session()->flash('success',"Page deleted successfully");
    //        return redirect()->route('page.index');
    //    } catch (\Throwable $th) {
    //           session()->flash('error',$th->getMessage());
    //        return redirect()->back()->withInput();
    //    }
    }
}
