<?php

namespace App\Http\Controllers\Admin\Marketing;

use App\Models\Province;
use Illuminate\Http\Request;
use App\Models\Marketing\SMS;
use Illuminate\Support\Facades\DB;
use App\Data\Customer\CustomerData;
use App\Actions\Marketing\SMSAction;
use App\Http\Controllers\Controller;
use App\Enum\Notification\PushNotificationEnum;
use App\Enum\Notification\PushNotificationForEnum;
use App\Models\District;
use App\Models\ImportEmailPhone;
use App\Models\New_Customer;
use Illuminate\Validation\Rule;

class SMSController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("admin.sms.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $sMS = new SMS();
        $data['sMS'] = $sMS;
        $data['statuses'] = (new PushNotificationEnum)->getAllValues();
        $data['for_users'] = (new PushNotificationForEnum)->getAllValues();
        $data['customers'] = (new CustomerData())->getData()['customers'];
        $data['phone_lists'] = ImportEmailPhone::whereNotNull('phone')->get();
        $provine_user=New_Customer::whereNotNull('province')->get();
        $provine_user=$provine_user->map(function($item){
                $pro = Province::where('eng_name',$item->province)->first();
            return [
                'id'=>$pro->id ?? '',
                'name'=>$item->province ,
                // 'user'=>New_Customer::where('province',$item->province)->whereNotNull('province')->get()
            ];
        }); 
        
        $data['addr']=collect($provine_user)->unique();
        // dd($data['addr']);
        return view("admin.sms.form",$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            "title" => "required",
            "content" => "required",
            "for" => Rule::in([1,2,3,7,8]),
            "status" => "required",
            "selection" => ($request->for == 2) ? "required" : "nullable",
        ],
        [
            "for.in" => "Please Select an User",
        ]);
        try{
           (new SMSAction($request))->store();
            session()->flash('success',"new SMS created successfully");
            DB::commit();
            return redirect()->route('sms.index');
        } catch (\Throwable $th) {
            session()->flash('error',$th->getMessage());
            DB::rollback();
            return redirect()->back()->withInput();

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Marketing\SMS  $sMS
     * @return \Illuminate\Http\Response
     */
    public function show(SMS $sMS)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Marketing\SMS  $sMS
     * @return \Illuminate\Http\Response
     */
    public function edit(SMS $sMS,$id)
    {
        $sMS =  SMS::find($id);
    //    return $smss = $sMS;
        $data['statuses'] = (new PushNotificationEnum)->getAllValues();
        $data['for_users'] = (new PushNotificationForEnum)->getAllValues();
        $data['customers'] = (new CustomerData())->getData()['customers'];
        $data['phone_lists'] = ImportEmailPhone::all();
        $provine_user=New_Customer::whereNotNull('province')->get();
        $provine_user=$provine_user->map(function($item){
                $pro = Province::where('eng_name',$item->province)->first();
            return [
                'id'=>$pro->id ?? '',
                'name'=>$item->province ,
                // 'user'=>New_Customer::where('province',$item->province)->whereNotNull('province')->get()
            ];
        }); 
        
        $data['addr']=collect($provine_user)->unique();
        return view("admin.sms.form",compact("sMS"),$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Marketing\SMS  $sMS
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {   
        $this->validate($request, [
            "title" => "required",
            "content" => "required",
            "for" => Rule::in([1,2,3,7,8]),
            "status" => "required",
            "selection" => ($request->for == 2) ? "required" : "nullable",
        ],
        [
            "for.in" => "Please Select an option from the Users dropdown",
            "selection.required" => "Please select the numbers of the customers to whom you want to send this SMS." 
        ]);
         DB::beginTransaction();
          try{
            (new SMSAction($request))->update($id);
            session()->flash('success',"new SMS created successfully");
            DB::commit();
            return redirect()->route('sms.index');

        } catch (\Throwable $th) {
            session()->flash('error',$th->getMessage());
            DB::rollback();
            return redirect()->back()->withInput();

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Marketing\SMS  $sMS
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            SMS::findOrFail($id)->delete();
            session()->flash('success',"SMS deleted successfully");
            return redirect()->route('sms.index');
        } catch (\Throwable $th) {
               session()->flash('error',$th->getMessage());
            return redirect()->back()->withInput();
        }
    }
}
