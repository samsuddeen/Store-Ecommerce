<?php
namespace App\Actions;

use App\Http\Controllers\Customer\SignupController;

class SMSAction
{
    protected $to, $client_name, $content;
    function __construct($to, $client_name=null, $content)
    {
        $this->to = $to;
        $this->client_name = $client_name;
        $this->content = $content;
    }
    public function sendSMS()
    {
        $args = http_build_query(array(
            'token' => 'v2_cW6mkM6ZFC29LdP3NNDWyalAoZf.2SJT',
            'from'  => 'Glass Pipe',
            'to'    =>  $this->to,
            'text'  => 'Dear  '.$this->client_name.',' . $this->content
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