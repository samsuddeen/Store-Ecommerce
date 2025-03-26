<?php

namespace App\Http\Controllers\EmailPhone;

use App\Http\Controllers\Controller;
use App\Imports\EmailPhoneImport;
use App\Models\ImportEmailPhone;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;


class EmailPhoneController extends Controller
{
    public function index(Request $request){
     $data['datas'] = ImportEmailPhone::orderBy('id','DESC')->orderBy('id','desc')->paginate(50);
        return view('admin.import_email_phone.index',$data);
    }

    public function import(Request $request){

         $request->validate([
            'file' => 'required|mimes:xls,xlsx'
        ]);
        $uploadedFile = $request->file('file');
        Excel::import(new EmailPhoneImport, request()->file('file'));
        return back()->with('success', 'User Imported Successfully.');

    }

    public function create(){


    }

    public function edit($id){

        $find = ImportEmailPhone::find($id);
        return view('admin.import_email_phone.edit',compact('find'));
    }

    public function update(Request $request,$id){

        $request->validate([
            'email' => 'required',
            'phone' => 'required',
        ]);
        $find = ImportEmailPhone::find($id);
        $find->email = $request->email;
        $find->phone = $request->phone;
        $find->save();
        return redirect()->route('importemail.index')->with('success','Data Edited Successfully!');

    }

    public function destroy($id){

        $find = ImportEmailPhone::find($id);
        $find->delete();
        return redirect()->route('importemail.index')->with('success','Data Deleted Successfully!');

    }
}
