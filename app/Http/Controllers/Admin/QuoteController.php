<?php

namespace App\Http\Controllers\Admin;

use App\Models\RFQ;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class QuoteController extends Controller
{
    public function index(){
        return view('admin.quote.index');
    }

    public function destroy($id){
        $datas=RFQ::find($id);
        try {
            $datas->delete();
            session()->flash('success', "Quote Request deleted successfully");
            return redirect()->route('quote.index');
        } catch (\Throwable $th) {
            session()->flash('success', $th->getMessage());
            return redirect()->back()->withInput();
        }
    }
}
