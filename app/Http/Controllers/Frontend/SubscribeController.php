<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Subscribe;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Actions\Notification\NotificationAction;
use App\Jobs\SendSubscriberMailJob;

class SubscribeController extends Controller
{
    protected $subscribe = null;
    public function __construct(Subscribe $subscribe)
    {
        $this->subscribe = $subscribe;
    }
    public function subscribe(Request $request)
    {

        $validate = Validator::make(
            $request->all(),
            [
                'email' => 'required|email|regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
                // 'g-recaptcha-response'=>'required'
            ]
        );

        if ($validate->fails()) { {
                return response()->json([
                    'message' => 'Please Enter Valid Email',
                    'error' => true
                ]);
            }
        };

      

        if (isset($request->email)) {
            $email = Subscribe::where('email', $request->email)->first();
            if (isset($email)) {
                return response()->json([
                    'message' => 'This email is already used',
                    'error' => true
                ]);
            } else {
                $data = $request->all();
                $data['admin_email'] = env('MAIL_FROM_ADDRESS');
                SendSubscriberMailJob::dispatch($data)->delay(now()->addSeconds(5));
                
                $this->subscribe->fill($data);
                $this->subscribe->save();
                return response()->json([
                    'message' => "Subscription completed",
                    'error' => false
                ]);
            }
        } else {
            return response()->json([
                'message' => "use other email",
                'error' => true
            ]);
        }
    }

    // public function sendMailtoSubscriber($data)
    // {
    //     Mail::send('email.subscriber.customer', $data, function ($message) use ($data) {
    //         $message->subject('Thanks for subscribing us.');
    //         $message->from($data['admin_email']);
    //         $message->to($data['email']);
    //     });
    // }

    // public function sendMailtoAdmin($data)
    // {
    //     Mail::send('email.subscriber.admin', $data, function ($message) use ($data) {
    //         $message->subject('Thanks for subscribing us.');
    //         $message->from($data['email']);
    //         $message->to($data['admin_email']);
    //     });
    // }
}
