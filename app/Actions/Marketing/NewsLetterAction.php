<?php

namespace App\Actions\Marketing;

use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Mail\EmailMarketing;
use Illuminate\Http\Request;
use App\Events\NewsLetterEvent;
use App\Models\Marketing\NewsLetter;
use Illuminate\Support\Facades\Mail;
use App\Models\Notification\PushNotification;
use App\Enum\Notification\PushNotificationEnum;
use App\Enum\Notification\PushNotificationForEnum;
use App\Models\ImportEmailPhone;

class NewsLetterAction
{
    protected $request;
    protected $input;
    function __construct(Request $request)
    {
        $this->request = $request;
        $this->input = $request->all();
        
        $this->input['created_by'] = auth()->user()->id;
        $this->input['slug'] = Str::slug($request->title . ' ' . rand(0000, 9999));
    }
    public function store()
    {   
        if ($this->request->for == 7) {
            // If for is equal to 7, encode email_selection and set selection to 'null'.
            $this->input['email_selection'] = json_encode($this->request->email_selection);
            $this->input['selection'] = null;
            $newsLetter = NewsLetter::create($this->input);
            $listofemail = json_decode($newsLetter->email_selection);

            if (!empty($listofemail)) {
                if ($this->request->status == PushNotificationEnum::PUSHED) {
                   
                    foreach ($listofemail as $key => $email) {
                        $email_individual = ImportEmailPhone::where('id', $email)->first();
                        Mail::to($email_individual->email)->send(new EmailMarketing($newsLetter));
                        // dd($email_individual->email);
                    }
                }
            }
        }
        //  elseif($this->request->for == 8){

        //     $this->input['phone_selection'] = json_encode($this->request->phone_selection);
        //     $this->input['selection'] = null;
        //     $this->input['email_selection'] = null;
        //     $newsLetter = NewsLetter::create($this->input);
        //     $listofphone = json_decode($newsLetter->phone_selection);
           

        //     if (!empty($listofphone)) {
        //         if ($this->request->status == PushNotificationEnum::PUSHED) {
                   
        //             foreach ($listofphone as $key => $phone) {
        //                 $phone_individual = ImportEmailPhone::where('id', $phone)->first();
        //                 $this->sendSMS( $phone_individual->phone,$newsLetter);
        //             }
        //         }
        //     }
        // }
        
        else {
            // Otherwise, encode selection and set email_selection to 'null'.
            $this->input['selection'] = json_encode($this->request->selection);
            $this->input['email_selection'] = null;
            $newsLetter = NewsLetter::create($this->input);

            // Create a NewsLetter instance using the prepared $this->input data.



            // dd($newsLetter);
            if ($this->request->status == PushNotificationEnum::PUSHED) {
                // dd($newsLetter);
                $this->input['pushed_date'] = Carbon::now();

                if ($this->request->for == (int)  PushNotificationForEnum::ALL) {
                    NewsLetterEvent::dispatch($newsLetter, $this->request->for);
                } else {
                    NewsLetterEvent::dispatch($newsLetter, $this->request->for, $this->request->selection);
                }
            }

            NewsLetterEvent::dispatch($newsLetter, $this->request->for, $this->request->selection);
        }
    }

    public function update(NewsLetter $newsLetter)
    {
        
        if ($this->request->for == 7) {
            // If for is equal to 7, encode email_selection and set selection to 'null'.
            $this->input['email_selection'] = json_encode($this->request->email_selection);
            $this->input['selection'] = null;
            $newsLetter->update($this->input);
            $listofemail = json_decode($newsLetter->email_selection);
            if (!empty($listofemail)) {
                if ($this->request->status == PushNotificationEnum::PUSHED) {
                   
                    foreach ($listofemail as $key => $email) {
                        $email_individual = ImportEmailPhone::where('id', $email)->first();
                        Mail::to($email_individual->email)->send(new EmailMarketing($newsLetter));
                        // dd($email_individual->email);
                    }
                }
            }
        }else{


            $newsLetter->update($this->input);
            if ($this->request->status == PushNotificationEnum::PUSHED) {
                $this->input['pushed_date'] = Carbon::now();
                if ($this->request->for == (int)  PushNotificationForEnum::ALL) {
                    NewsLetterEvent::dispatch($newsLetter, $this->request->for);
                } else {
                    NewsLetterEvent::dispatch($newsLetter, $this->request->for, $this->request->selection);
                }
            }
            NewsLetterEvent::dispatch($newsLetter, $this->request->for, $this->request->selection);

        }
    }

    public function sendSMS($to,$newsLetter)
    {
        $description = strip_tags($newsLetter->description);
        // dd($newsLetter);
        $args = http_build_query(array(
            'token' => 'v2_cW6mkM6ZFC29LdP3NNDWyalAoZf.2SJT',
            'from'  => 'Glass Pipe',
            'to'    =>  $to,
            'text'  => 'Dear  '.$newsLetter->title.',' . $description. ',' .$newsLetter->summary
        ));

        $url = "http://api.sparrowsms.com/v2/sms/";

        # Make the call using API.
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $args);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // Response
        $response = curl_exec($ch);
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

    }
}
