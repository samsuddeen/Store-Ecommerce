<?php

namespace App\Http\Controllers\Admin\Marketing;

use App\Actions\Marketing\NewsLetterAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Data\Customer\CustomerData;
use App\Http\Controllers\Controller;
use App\Models\Marketing\NewsLetter;
use App\Http\Requests\PushNotificationRequest;
use App\Enum\Notification\PushNotificationEnum;
use App\Enum\Notification\PushNotificationForEnum;
use App\Models\ImportEmailPhone;

class NewsLetterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("admin.news-letter.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $newsLetter = new NewsLetter();
        $data['newsLetter'] = $newsLetter;
        $data['statuses'] = (new PushNotificationEnum)->getAllValues();
        $data['for_users'] = (new PushNotificationForEnum)->getAllValues();
        $data['customers'] = (new CustomerData())->getData()['customers'];
        $data['emails'] = ImportEmailPhone::whereNotNull('email')->select('email','id','phone' )->get();
        return view("admin.news-letter.form",$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PushNotificationRequest $request)
    {
        // DB::beginTransaction();
        // try{
            // return $request->all();
           (new NewsLetterAction($request))->store();
           session()->flash('success',"new News Letter created successfully");
            // DB::commit();
            return redirect()->route('news-letter.index');
        // } catch (\Throwable $th) {
        //    session()->flash('error',$th->getMessage());
        //     DB::rollback();
        //     return redirect()->back()->withInput();

        // }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Marketing\NewsLetter  $newsLetter
     * @return \Illuminate\Http\Response
     */
    public function show(NewsLetter $newsLetter)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Marketing\NewsLetter  $newsLetter
     * @return \Illuminate\Http\Response
     */
    public function edit(NewsLetter $newsLetter)
    {   
        $data['newsLetter'] = $newsLetter;
        $data['statuses'] = (new PushNotificationEnum)->getAllValues();
        $data['for_users'] = (new PushNotificationForEnum)->getAllValues();
        $data['customers'] = (new CustomerData())->getData()['customers'];
        $data['emails'] = ImportEmailPhone::select('email','id','phone')->get();
        return view("admin.news-letter.form",$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Marketing\NewsLetter  $newsLetter
     * @return \Illuminate\Http\Response
     */
    public function update(PushNotificationRequest $request, NewsLetter $newsLetter)
    {
       
          try{
            (new NewsLetterAction($request))->update($newsLetter);
           session()->flash('success', $newsLetter->title." Updated successfully");
            DB::commit();
            return redirect()->route('news-letter.index');

        } catch (\Throwable $th) {
           session()->flash('error',$th->getMessage());
            DB::rollback();
            return redirect()->back()->withInput();

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Marketing\NewsLetter  $newsLetter
     * @return \Illuminate\Http\Response
     */
    public function destroy(NewsLetter $newsLetter)
    {
        try{
            $newsLetter->delete();
            session()->flash('success',"NewsLetter deleted successfully");
            return redirect()->route('news-letter.index');
        } catch (\Throwable $th) {
              session()->flash('error',$th->getMessage());
            return redirect()->back()->withInput();
        }
    }
}
