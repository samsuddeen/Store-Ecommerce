<?php

namespace App\Http\Controllers\Admin\Notification;

use App\Data\Date\DateData;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Data\Customer\CustomerData;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Notification\PushNotification;
use App\Http\Requests\PushNotificationRequest;
use App\Enum\Notification\PushNotificationEnum;
use App\Enum\Notification\PushNotificationForEnum;
use App\Actions\Notification\PushNotificationAction;
use App\Data\Admin\NotificationDetail;
use App\Helpers\Utilities;
class PushNotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("admin.push-notification.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pushNotification = new PushNotification();
        $data['pushNotification'] = $pushNotification;
        $data['statuses'] = (new PushNotificationEnum)->getAllValues();
        $data['for_users'] = (new PushNotificationForEnum)->getAllValues();
        $data['customers'] = (new CustomerData())->getData()['customers'];
        return view("admin.push-notification.form", $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PushNotificationRequest $request)
    {
        $request['for']=1;
        DB::beginTransaction();
        try {
            (new PushNotificationAction($request))->store();
            session()->flash('success', "new Push Notification created successfully");
            DB::commit();
            return redirect()->route('push-notification.index');
        } catch (\Throwable $th) {
            session()->flash('error', $th->getMessage());
            DB::rollback();
            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Notification\PushNotification  $pushNotification
     * @return \Illuminate\Http\Response
     */
    public function show(PushNotification $pushNotification)
    {
        dd($pushNotification);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Notification\PushNotification  $pushNotification
     * @return \Illuminate\Http\Response
     */
    public function edit(PushNotification $pushNotification)
    {
        $data['pushNotification'] = $pushNotification;
        $data['statuses'] = (new PushNotificationEnum)->getAllValues();
        $data['for_users'] = (new PushNotificationForEnum)->getAllValues();
        $data['customers'] = (new CustomerData())->getData()['customers'];
        return view("admin.push-notification.form", $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Notification\PushNotification  $pushNotification
     * @return \Illuminate\Http\Response
     */
    public function update(PushNotificationRequest $request, PushNotification $pushNotification)
    {
        $request['for']=1;
        DB::beginTransaction();
        try {
            (new PushNotificationAction($request))->update($pushNotification);
            session()->flash('success', "new Push Notification updated successfully");
            DB::commit();
            return redirect()->route('push-notification.index');
        } catch (\Throwable $th) {
            session()->flash('error', $th->getMessage());
            DB::rollback();
            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Notification\PushNotification  $pushNotification
     * @return \Illuminate\Http\Response
     */
    public function destroy(PushNotification $pushNotification)
    {
        try {
            $pushNotification->delete();
            session()->flash('success', "PushNotification deleted successfully");
            return redirect()->route('push-notification.index');
        } catch (\Throwable $th) {
            session()->flash('error', $th->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function viewNotifications(Request $request)
    {
       
        if($request->ajax())
        {
            $filters = $request->all();

            if(!Arr::get($filters, 'type')){
                $filters['type'] = null;
            }

           $data=(new NotificationDetail($filters))->getData();
        //    dd($data->toArray());
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('notification_title', function ($row) {
                    return $row->title;
                })
                ->addColumn('date', function ($row) {
                    return  $row->created_at;
                })
                ->addColumn('action', function ($row) {
                    $show =  Utilities::button(href: route('notification.show', $row->id), icon: "eye", color: "info", title: 'Show Notification');
                    return  $show;
                })
                
                ->rawColumns(['date','notification_title','action'])
                ->make(true);
            }
            $data['filters'] = $request->all();
            $dateData = new DateData();
            $data['months'] = $dateData->getMonths();
            $data['years'] = $dateData->getYears();
            
            return view('notification.index',$data);
    }
}
