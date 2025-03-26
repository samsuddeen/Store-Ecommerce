<?php

namespace App\Jobs;

use App\Actions\SMSAction;
use App\Models\New_Customer;
use App\Models\Marketing\SMS;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use App\Enum\Notification\PushNotificationForEnum;
use App\Models\ImportEmailPhone;

class SendSMSJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $sMS;
    public $check;
    public $selection;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(SMS $sMS, $check=1, $selection=[])
    {
        //
      
        
        info('job construct');
        $this->sMS = $sMS;
        $this->check = $check;
        $this->selection = $selection;
        $this->handle();
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
        // dd($this->selection);

        info($this->selection);
        try {
            foreach($this->selection as $selected_id){
             
                $customer = ImportEmailPhone::find($selected_id);
                $this->sendSMS($this->sMS , $customer->phone);
                // $customer = New_Customer::find($selected_id);
                // (new SMSAction($customer->phone, $customer->name, $this->sMS->content));
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

            // $customers = New_Customer::all();
            $customers = ImportEmailPhone::all();
            info(json_encode($customers));
            
            foreach ($customers as $key => $customer) {
                $this->sendSMS($this->sMS , $customer->phone);
                // (new SMSAction($customer->phone, $customer->name, $this->sMS->content));
            }
            info('sent to the all users');
        } catch (\Throwable $th) {
           info($th->getMessage());
        }
    }


    public function sendSMS($data , $to)
    {
        $content = Strip_tags($data->content);
        $args = http_build_query(array(
            'token' => 'v2_cW6mkM6ZFC29LdP3NNDWyalAoZf.2SJT',
            'from'  => 'Glass Pipe',
            'to'    =>  $to,
            'text' => 'Dear Customer ' . "\n" . $data->title . "\n" . $content . "\n" . $data->url,
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



