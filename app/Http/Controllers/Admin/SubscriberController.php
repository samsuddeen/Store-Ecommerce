<?php

namespace App\Http\Controllers\Admin;

use App\Models\Subscribe;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SubscriberController extends Controller
{
    public function index(){
        return view('admin.subscriber.index');
    }

    public function destroy($id){
        $datas=Subscribe::find($id);
        try {
            $datas->delete();
            session()->flash('success', "Subscriber deleted successfully");
            return redirect()->route('subscriber.index');
        } catch (\Throwable $th) {
            session()->flash('success', $th->getMessage());
            return redirect()->back()->withInput();
        }
    }
}
