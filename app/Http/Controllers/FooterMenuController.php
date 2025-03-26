<?php

namespace App\Http\Controllers;

use App\Models\footermenu;
use App\Models\footertitle;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class FooterMenuController extends Controller
{
    public function index()
    {
        $datas=footertitle::all();
        return view('front.footermenu.index',compact('datas'));
    }
    public function store(Request $request)
    {


        // $validated = $request->validate([
        //     // 'title' => 'required|max:255',
        //     // 'description'=>'required',
        //     // 'content_type'=>'required',
        //     // 'image' => 'required|mimes:jpeg,jpg,png,gif|required|max:10000',
        // ]);


        $datas = new footertitle();
        $datas->title = $request->title;
        $datas->status = $request->status;
        $datas->save();
        return redirect('footermenu')->with('success','Footer added successfully');

    }
    public function create()
    {
        $footer = footertitle::all();
        return view('front.footermenu.create', compact('footer'));
    }

    public function edit($id)
    {
        $footer=footertitle::findorFail($id);

        return view('front.footermenu.edit', compact('footer'));
    }
    public function update(Request $request)
    {
        $footer = footertitle::findOrFail($request->id);




        $footer->update([

            'title' => $request->title,


            // 'image' => $image,
        ]);
        return redirect('footermenu')->with('update', 'Footer updated successfully.');
        // return redirect('blog');

    }
    public function destroy($id){

        $datas=footertitle::find($id);
        $datas->delete();

        return redirect('/footermenu');
    }
    public function status($id)
    {
        $datas = footertitle::find($id);
        if ($datas->status == 1) {
            $status = 0;
        } else {
            $status = 1;
        }
        $value = array('status' => $status);
        DB::table('footertitles')->where('id', $id)->update($value);
        return redirect('footermenu');
    }


}

