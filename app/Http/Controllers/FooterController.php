<?php

namespace App\Http\Controllers;
use App\Models\footer;
use App\Models\footertitle;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class FooterController extends Controller
{

    public function index()
    {

        $datas=footer::all();
        return view('front.index',compact('datas'));
    }
    public function store(Request $request)
    {

        $validated = $request->validate([
            'title' => 'required|max:255',
            'description'=>'required',
            'content_type'=>'required',        ]);

        if ($request->hasFile('cover_image')) {
            $image = $request->file('cover_image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('cover_image'), $imageName);
        } else {
            $imageName = null;
        }

        $datas = new footer();
        $datas->title = $request->title;
        $datas->description = $request->description;
        $datas->content_type=$request->content_type;
        $datas->cover_image=$imageName;
        $datas->slug=Str::slug($request->title);
        $datas->position=$request->position;
        $datas->external_link=$request->external_link;
        $datas->save();
        return redirect('footer')->with('success','Footer added successfully');

    }
    public function create()
    {
        $footermenu=footertitle::all();
        $footer = footer::all();
        return view('front.create', compact('footer','footermenu'));
    }
    public function show($id)
    {
        $footer=footer::find($id);
        return $footer;
    }

    public function details($id)
    {

        $footers = footer::where('slug',$id)->first();        
        return view('front.detail',compact('footers'));
    }

    public function edit($id)
    {
        $footermenu=footertitle::all();
        $footer=footer::all();
        $footers = footer::findOrFail($id);
        return view('front.edit', compact('footers','footer','footermenu'));
    }
    public function update(Request $request)
    {
        $footer = footer::findOrFail($request->id);
        if ($request->hasFile('cover_image')) {
            $destination = 'cover_image' . $footer->cover_image;
            if (File::exists($destination)) {
                File::delete($destination);
            }
            $image = $request->file('cover_image');
            $extension = $image->getClientOriginalExtension();
            $imageName = time() . '.' . $extension;
            $image->move('cover_image', $imageName);
            $footer->cover_image = $imageName;
        } else {
            $imageName = $footer->cover_image;
        }


        $footer->update([
            'slug'=>$request->slug=Str::slug($request->title),
            'description'=>$request->description,
            'title' => $request->title,
            'content_type' => $request->content_type,
            'position' => $request->position,
            'external_link' => $request->external_link,

            // 'image' => $image,
        ]);
        return redirect('footer')->with('update', 'Footer updated successfully.');
        // return redirect('blog');

    }

    public function destroy($id){

        $datas=footer::find($id);
        $datas->delete();

        return redirect('/footer');
    }
    public function status($id)
    {
        $datas = footer::find($id);
        if ($datas->status == 1) {
            $status = 0;
        } else {
            $status = 1;
        }
        $value = array('status' => $status);
        DB::table('footers')->where('id', $id)->update($value);
        return redirect('footer');
    }


}

