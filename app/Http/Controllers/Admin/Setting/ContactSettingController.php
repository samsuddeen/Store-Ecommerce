<?php

namespace App\Http\Controllers\Admin\Setting;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Enum\Setting\ContactTypeEnum;
use App\Models\Setting\ContactSetting;
use Illuminate\Support\Facades\Validator;


class ContactSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("admin.setting.contact.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $contactSetting = new ContactSetting();
        $data['contactSetting'] = $contactSetting;
        $data['contact_types'] = (new ContactTypeEnum)->getAllValues();
        return view("admin.setting.contact.form", $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            "type" =>"required",
            "contact_no" => "required"
        ]);
        DB::beginTransaction();
        $test_contact = ContactSetting::all();
        $contact_num = count($test_contact);
        try {
            if ($contact_num < 4) {
                ContactSetting::create($request->all());
                session()->flash('success', "new ContactSetting created successfully");
                DB::commit();
                return redirect()->route('contact-setting.index');
            } else {
                session()->flash('error', 'Many Contact are added, Please edit or delete old Contact.');
                return redirect()->route('contact-setting.index');
            }
        } catch (\Throwable $th) {
            session()->flash('error', $th->getMessage());
            DB::rollback();
            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Setting\ContactSetting  $contactSetting
     * @return \Illuminate\Http\Response
     */
    public function show(ContactSetting $contactSetting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Setting\ContactSetting  $contactSetting
     * @return \Illuminate\Http\Response
     */
    public function edit(ContactSetting $contactSetting)
    {
        $data['contactSetting'] = $contactSetting;
        $data['contact_types'] = (new ContactTypeEnum)->getAllValues();
        return view("admin.setting.contact.form", $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Setting\ContactSetting  $contactSetting
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ContactSetting $contactSetting)
    {
        DB::beginTransaction();
        try {
            $contactSetting->update($request->all());
            session()->flash('success', "new ContactSetting created successfully");
            DB::commit();
            return redirect()->route('contact-setting.index');
        } catch (\Throwable $th) {
            session()->flash('error', $th->getMessage());
            DB::rollback();
            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Setting\ContactSetting  $contactSetting
     * @return \Illuminate\Http\Response
     */
    public function destroy(ContactSetting $contactSetting)
    {
        try {
            $contactSetting->delete();
            session()->flash('success', "ContactSetting deleted successfully");
            return redirect()->route('contact-setting.index');
        } catch (\Throwable $th) {
            session()->flash('error', $th->getMessage());
            return redirect()->back()->withInput();
        }
    }



    public function updateStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'contact_setting_id' => 'required',
        ]);
        if ($validator->fails()) {
            return back()->withInput()->with('error', 'Something is wrong');
        }
        DB::beginTransaction();
        try {
            $socialSetting = ContactSetting::where('id', $request->contact_setting_id)->update(['status' => $request->status]);
            DB::commit();
            session()->flash('success', 'Successfully Status has been changed');
            return redirect()->route('contact-setting.index');
        } catch (\Throwable $th) {
            DB::rollBack();
            session()->flash('error', 'Sorry something is wrong');
            return back()->withInput();
        }
    }
}
