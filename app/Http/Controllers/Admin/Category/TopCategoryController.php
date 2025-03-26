<?php

namespace App\Http\Controllers\Admin\Category;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\TopCategoryRequest;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Throwable;
use App\Models\Admin\TopCategory\TopCategory;
use App\Http\Requests\UpdateTopCategoryRequest;
use App\Actions\Trash\TrashAction;
class TopCategoryController extends Controller
{

    protected $top_category=null;

    public function __construct(TopCategory $top_category)
    {
        $this->top_category=$top_category;
    }
    public function index()
    {
        $top_category=TopCategory::get();
        return view('admin.topcategory.index',compact('top_category'));
    }

    public function create()
    {

        
        $category=Category::get();
       return view('admin.topcategory.form',compact('category'));
    }

    public function store(TopCategoryRequest $request)
    {
        DB::beginTransaction();

        try{
            $already_exists=TopCategory::where('category_id',$request->category_id)->first();
            if($already_exists)
            {
                session()->flash('error','Category Already Exists !!');
            return redirect()->back();
            }
            $data=$request->all();
            $this->top_category->fill($data);
            $this->top_category->save();

            DB::commit();
            session()->flash('success','Category Added Successfully !!');
            return redirect()->back();
        }catch(\Throwable $th)
        {
            session()->flash('error',$th->getMessage());
            return redirect()->back();
        }
    }

    public function edit(Request $request,$id)
    {
       
        $this->top_category=$this->top_category->where('id',$id)->first();
        $category=Category::get();
        return view('admin.topcategory.form',compact('category'))
        ->with('data',$this->top_category);
    }

    public function update(UpdateTopCategoryRequest $request,$id)
    {
        $this->top_category=$this->top_category->where('id',$id)->first();
        $data=$request->all();
        DB::beginTransaction();
        try{
            $this->top_category->fill($data);
            $this->top_category->save();
            DB::commit();
            session()->flash('success','Category Updated Successfully !!');
            return redirect()->back();
        }catch(\Throwable $th){
            session()->flash('error',$th->getMessage());
            return redirect()->back();
        }
    }

    public function destroy(Request $request,$id)
    {
        $this->top_category=$this->top_category->where('id',$id)->first();
        DB::beginTransaction();
        try{
            // (new TrashAction($this->top_category, $this->top_category->id))->makeRecycle();
            $this->top_category->delete();
            DB::commit();
            session()->flash('success','Category deleted Successfully !!');
            return redirect()->back();
        }catch(\Throwable $th){
            session()->flash('error',$th->getMessage());
            return redirect()->back();
        }
    }
}
