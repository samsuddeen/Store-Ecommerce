<?php

namespace App\Http\Controllers;

use App\Models\header;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class HeaderController extends Controller
{
    public function index()
    {
        $datas=header::all();
        return view('front.header.index',compact('datas'));
    }
    public function create(){
        return view('front.header.create');
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            // 'title' => 'required|max:255',
            // 'description'=>'required',
            // 'content_type'=>'required',
            // 'image' => 'required|mimes:jpeg,jpg,png,gif|required|max:10000',
        ]);

        if ($request->hasFile('icon_image')) {
            $image = $request->file('icon_image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('icon_image'), $imageName);
        } else {
            $imageName = null;
        }

        $datas = new header();
        $datas->title = $request->title;
        $datas->status = $request->status;
        $datas->icon_image=$imageName;
        $datas->save();
        return redirect('header')->with('success','Header added successfully');

    }
    public function edit($id)
    {
        $header = header::findOrFail($id);
        return view('front.header.edit', compact('header'));
    }
    public function update(Request $request)
    {
        $header = header::findOrFail($request->id);
        if ($request->hasFile('icon_image')) {
            $destination = 'icon_image' . $header->icon_image;
            if (File::exists($destination)) {
                File::delete($destination);
            }
            $image = $request->file('icon_image');
            $extension = $image->getClientOriginalExtension();
            $imageName = time() . '.' . $extension;
            $image->move('icon_image', $imageName);
            $header->icon_image = $imageName;
        } else {
            $imageName = $header->icon_image;
        }


        $header->update([
            'title' => $request->title,

            // 'image' => $image,
        ]);
        return redirect('header')->with('update', 'Header updated successfully.');
        // return redirect('blog');

    }
    public function status($id){
        $status = header::find($id);
        if($status->status == 1){
            $status =0;
        }else{
            $status=1;
        }
        $value = array('status' => $status);

        DB::table('headers')->where('id',$id)->update($value);
        return redirect('header');
    }
    public function destroy($id){
        $datas = header::find($id);
        $datas->delete();
        return redirect('/header');
    }
}
