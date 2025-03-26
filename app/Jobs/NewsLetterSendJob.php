<?php

namespace App\Jobs;

use App\Enum\Notification\PushNotificationEnum;
use App\Enum\Notification\PushNotificationForEnum;
use Illuminate\Bus\Queueable;
use App\Models\Marketing\NewsLetter;
use App\Models\New_Customer;
use App\Models\Setting;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class NewsLetterSendJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $newsLetter;
    public $check;
    public $selection;
    public $mail_from;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(NewsLetter $newsLetter, $check=1, $selection=[])
    {
        //
        info('job construction');
        $this->newsLetter = $newsLetter;
        $this->check = $check;
        $this->selection = $selection;
        info($this->check);
        $email = Setting::where('key', 'email')->first();
        if(!$email){
            $this->mail_from = env('MAIL_FROM_ADDRESS');
        }else{
            $this->mail_from = $email->value;
        }
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        info('job construction handling');
        if((int)$this->check !== (int) PushNotificationForEnum::ALL){
            $this->sendSelected();
        }else{
            $this->sendToAll();
        }
    }
    private function sendSelected()
    {
        info('sending to the selected users');
        info($this->selection);
        try {
            foreach($this->selection as $selected_id){
                $emails = New_Customer::find($selected_id);
                Mail::send('admin.news-letter.email', collect($this->newsLetter)->toArray(), function ($message) use($emails)  {
                    $message->from($this->mail_from);
                    $message->subject($this->newsLetter->title);
                    $message->to($emails->email);
                });
            }

            info('sent to the selected users');
        } catch (\Throwable $th) {
            info($th->getMessage());
        }
    }
    private function sendToAll()
    {
        info('sending to the all users');
        try {
            $emails = New_Customer::where('status', 1)->orderBy('name')->get();
            $emails = collect($emails)->pluck('email')->toArray();
            Mail::send('admin.news-letter.email', collect($this->newsLetter)->toArray(), function ($message) use($emails){
                $message->subject($this->newsLetter->title);
                $message->cc($emails);
            });
            info('sent to the all users');
        } catch (\Throwable $th) {
           info($th->getMessage());
        }
    }
}



