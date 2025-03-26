<?php

namespace App\Http\Controllers\Admin;

use App\Models\Tag;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Requests\TagRequest;
use App\Actions\Trash\TrashAction;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Actions\Admin\TagStoreAction;
use App\Http\Requests\TagUpdateRequest;
use Illuminate\Support\Facades\Validator;

class TagController extends Controller
{
    protected $tag;
    function __construct(Tag $tag)
    {
        $this->tag = $tag;
        $this->middleware(['permission:tag-read'], ['only' => ['index']]);
        $this->middleware(['permission:tag-create'], ['only' => ['create', 'store']]);
        $this->middleware(['permission:tag-edit'], ['only' => ['edit', 'update']]);
        $this->middleware(['permission:tag-delete'], ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("admin.tag.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tag = new Tag();
        return view("admin.tag.form", compact("tag"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TagRequest $request)
    {
        DB::beginTransaction();
        $input = $request->all();
        $input['user_id'] = $request->user()->id;
        $input['thumbnail'] = $this->tag->getThumbs($request->image);
        $input['slug'] = Str::slug($request->title);
        try {
            Tag::create($input);
            session()->flash('success', "new Tag created successfully");
            DB::commit();
            return redirect()->route('tag.index');
        } catch (\Throwable $th) {
            session()->flash('error', $th->getMessage());
            DB::rollback();
            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function show(Tag $tag)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function edit(Tag $tag)
    {
        return view("admin.tag.form", compact("tag"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function update(TagUpdateRequest $request, Tag $tag)
    {
        DB::beginTransaction();
        $input = $request->all();
        $input['user_id'] = $request->user()->id;
        $input['thumbnail'] = $this->tag->getThumbs($request->image);
        $input['slug'] = Str::slug($request->title);
        try {
            $tag->update($input);
            session()->flash('success', $input['title']." Tag updated successfully");
            DB::commit();
            return redirect()->route('tag.index');
        } catch (\Throwable $th) {
            session()->flash('error', $th->getMessage());
            DB::rollback();
            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tag $tag)
    {
        try {
            (new TrashAction($tag, $tag->id))->makeRecycle();
            $tag->delete();
            session()->flash('success', "Tag deleted successfully");
            return back();
        } catch (\Throwable $th) {
            session()->flash('error', $th->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function directTagAdd(Request $request)
    {
        $rules = array(
            'title' => 'required|string|unique:tags,title',
            'image'=>'required|string',
            'publishStatus'=>'required|in:0,1',
            'alt_img'=>'nullable|string'
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

        $input = $request->all();
        $input['user_id'] = $request->user()->id;
        $input['thumbnail'] = $this->tag->getThumbs($request->image);
        $input['slug'] = Str::slug($request->title);


        DB::beginTransaction();
        try{
            $data=(new TagStoreAction($input))->storeTag();
            DB::commit();
            $request->session()->flash('success','Tag Added Successfully !!');
            $response_values = array(
                'error' => false,
                'msg'=>'Tag Added Successfully !!'
            );
            // return response()->json($response_values, 200);
            return response()->json(['status'=>200, 'value'=>$response_values, 'data'=>$data]);
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
