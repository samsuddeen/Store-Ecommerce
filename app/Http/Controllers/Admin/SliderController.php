<?php

namespace App\Http\Controllers\Admin;

use App\Models\Slider;
use App\Traits\ImageTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Actions\Trash\TrashAction;

class SliderController extends Controller
{
    use ImageTrait;

    public function __construct()
    {
    }

    public function AjaxImageUpload(Request $request)
    {
        $formImage = "image";
        $files = $request->file('image');
        $this->imageUpload($request, $files, 'slider', 'sliders', $formImage);
    }


    public function index(Request $request)
    {
        $sliders = Slider::orderBy('order', 'asc')->paginate(10);
        return view('admin.list.slider', compact('sliders'));
    }

    public function fetch(Request $request)
    {
        $sliderName = $request->sliderName;

        $sliders = Slider::orderBy('id', 'asc')
            ->where('delete_status', '0')
            ->when($sliderName, function ($query, $sliderName) {
                return $query->where("title", "LIKE", "%$sliderName%");
            })
            ->paginate(10);
        return view('admin.list.ajaxlist.slider', compact('sliders'));
    }

    public function create()
    {
        return view('admin.form.slider');
    }

    public function store(Request $request)
    {
        
        $request->validate([
            'title' => 'required',
            'image' => 'required'
        ]);
        $slider =  new Slider();

        $slider->title = request('title');
        $slider->body = request('body');
        $slider->link = request('link');
        if($request->show_mob==1)
        {
            $slider->show_mob=1;
        }
        else
        {
            $slider->show_mob=0;
        }
       

        $slider->meta_desc = request('meta_desc');
        $slider->meta_keyword = request('meta_keyword');
        $slider->meta_title = request('meta_title');
        $slider->alt_img = request('alt_img');

        $slider->publish_status =  request('publish_status');
        $slider->hide_status =  request('hide_status');
        // dd($request->session()->get('ajaximage'));
        $slider->image = $request->image;
        // if($request->hasFile('image')){
        //     $file = $request->image;
        //     $filename = time() . '.' . $file->getClientOriginalExtension();
        //     $path = $file->storeAs('photos', $filename);
        //     $slider->image = $path;
        // }

        $slider->save();

        $request->session()->forget('ajaximage');

        return redirect()->route('slider.index')->with('success', 'Slider added successsfully');
    }

    public function edit($id)
    {
        $slider = Slider::where('id', $id)->first();
        return view('admin.form.slider', compact('slider'));
    }


    public function update(Request $request, $id)
    {
        // dd($request->all());
        $slider = Slider::where('id', $id)->first();

        $this->validate(request(), [
            'title' => 'required',
            'image' => 'required'
        ]);
        if($request->show_mob==1)
        {
            $mob=1;
        }
        else
        {
            $mob=0;
        }

        if ($request->image) {
            if (isset($slider->image)) {
                Storage::delete($slider->image);
            }
            $data = ([
                'title' => request('title'),
                'body' => request('body'),
                'link' => request('link'),
                'image' => $request->image,
                'meta_desc' => request('meta_desc'),
                'meta_keyword' => request('meta_keyword'),
                'meta_title' => request('meta_title'),
                'alt_img' => request('alt_img'),
                'publish_status' => request('publish_status'),
                'hide_status' => request('hide_status'),
                'show_mob'=>$mob
            ]);
        } else {
            $data = ([
                'title' => request('title'),
                'body' => request('body'),
                'link' => request('link'),
                'meta_desc' => request('meta_desc'),
                'meta_keyword' => request('meta_keyword'),
                'meta_title' => request('meta_title'),
                'alt_img' => request('alt_img'),
                'publish_status' => request('publish_status'),
                'hide_status' => request('hide_status'),
                'show_mob'=>$mob
            ]);
        }

        Slider::where('id', $id)->update($data);

        // $request->session()->forget('ajaximage');

        return redirect()->route('slider.index')->with('success', 'Slider updated successfully');
    }

    public function destroy($id)
    {
        $slider = Slider::where('id', $id)->first();
        if (isset($slider->image)) {
            Storage::delete($slider->image);
        }
        (new TrashAction($slider, $slider->id))->makeRecycle();
        $slider->delete();
        return redirect()->back()->with('success', 'Slider deletion completed.');
    }

    public function ajaxSliderImgDestroy(Request $request)
    {
        $slider_id = $request->deleteImage;

        $slider = Slider::where('id', $slider_id)->first();

        if ($slider != null) {
            $image = $slider->image;
            @unlink('uploads/' . 'sliders/' . $image);
            $data1 = ([
                'image' => null,
            ]);
            Slider::where('id', $slider_id)->update($data1);
        }

        return response()->json([
            "status" => "success",
            "message" => " image successfully deleted"
        ], 200);
    }





    public function updateOrder(Request $request)
    {
        $order = $request->input('order');
        foreach ($order as $key => $id) {
            Slider::where('id', $id)->update(['order' => $key]);
        }
        return response()->json(['success' => true]);
    }
}
