<?php

namespace App\Http\Controllers\Admin;

use App\Models\Brand;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Actions\Trash\TrashAction;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Actions\Admin\BrandStoreAction;
use Illuminate\Support\Facades\Validator;

class BrandController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:brand-read'], ['only' => ['index']]);
        $this->middleware(['permission:brand-create'], ['only' => ['create', 'store']]);
        $this->middleware(['permission:brand-edit'], ['only' => ['edit', 'update']]);
        $this->middleware(['permission:brand-delete'], ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("admin.brand.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $brand = new Brand();
        return view("admin.brand.form", compact("brand"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $data=$request->all();
            $request['slug']=Str::slug($request->name);
            Brand::create($request->all());
            session()->flash('success', "new Brand created successfully");
            return redirect()->route('brand.index');
        } catch (\Throwable $th) {
            session()->flash('success', $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function show(Brand $brand)
    {
        return view("admin.brand.show", compact("brand"));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function edit(Brand $brand)
    {
        return view("admin.brand.form", compact("brand"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Brand $brand)
    {

        try {
            $brand->update($request->all());
            session()->flash('success', "new Brand created successfully");
            return redirect()->route('brand.index');
        } catch (\Throwable $th) {
            session()->flash('success', $th->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function destroy(Brand $brand)
    {
        try {
            (new TrashAction($brand, $brand->id))->makeRecycle();
            $brand->delete();
            session()->flash('success', "Brand deleted successfully");
            return redirect()->route('brand.index');
        } catch (\Throwable $th) {
            session()->flash('success', $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function directBrandAdd(Request $request)
    {
        $rules = array(
            'name' => 'required|unique:brands,name|string',
            'image'=>'required|string',
            'status'=>'required|in:0,1'
        );
        $v = Validator::make($request->all(), $rules);
        if (!$v->passes()) {
            $messages = $v->messages();
            foreach ($rules as $key => $value) {
                $verrors[$key] = $messages->first($key);
            }
            $response_values = array(
                'validate' => true,
                'validation_failed' => 1,
                'errors' => $verrors
            );
            return response()->json($response_values, 200);
        }

        DB::beginTransaction();
        try{
            $data=(new BrandStoreAction($request))->storeBrand();
            // dd($data);
            DB::commit();
            $request->session()->flash('success','Brand Added Successfully !!');
            $response_values = array(
                'error' => false,
                'msg'=>'Brand Added Successfully !!'
            );
            return response()->json(['status'=>200, 'response_values'=>$response_values, 'data'=>$data]);
            // return response()->json($response_values, 200);
        }catch(\Throwable $th)
        {
            DB::rollBack();
            $request->session()->flash('error','Something Went Wrong!!');
            $response_values = array(
                'error' => true,
                'msg'=>'Something Went Wrong !!'
            );
            return response()->json($response_values, 200);
        }
    }
}
