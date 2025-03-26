<?php

namespace App\Http\Controllers\Frontend;

use App\Models\RFQ;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RFQController extends Controller
{
    public function store(Request $request){
        $request->validate([
            'name'=> 'required',
            'quantity' => 'required',
            'type'=>'required',
            'email'=>'required',
            'phone'=>'required',
        ]);
        RFQ::create($request->all());
        return redirect()->back()->with('success','Your Quote is sent');
    }
}