<?php

namespace App\Actions\Setting\SMTP;

use App\Models\SMTP\SMTP;
use Illuminate\Http\Request;

class SMTPAction
{
    protected $request;
    function __construct(Request $request)
    {
        $this->request = $request;
    }
    public function store()
    {
        $data = [
            'mail_user_name' => $this->request->mail_user_name,
            'mail_driver' => $this->request->mail_driver ?? "smtp",
            'mail_host' => $this->request->mail_host ?? "smtp.gmail.com",
            'mail_port' => $this->request->mail_port ?? "465",
            'mail_password' => $this->request->mail_password,
            'mail_from_address' => $this->request->mail_from_address ?? null,
            'mail_from_name' => $this->request->mail_from_name ?? "Jhigu",
            'mail_encryption' => $this->request->mail_encryption ?? "ssl",
            'is_default' => $this->request->is_default ?? false,
        ];
        $smtp = SMTP::create($data);
        if ($this->request->has('is_default')) {
            SMTP::where('id', '!=', $smtp->id)->update([
                'is_default'=>false,
            ]);
            foreach($data as $key=>$value){
                $this->putPermanentEnv(strtoupper($key), $value);
            }
        }
    }
    public function update(SMTP $sMTP)
    {
        $data = [
            'mail_user_name' => $this->request->mail_user_name,
            'mail_driver' => $this->request->mail_driver ?? "smtp",
            'mail_host' => $this->request->mail_host ?? "smtp.gmail.com",
            'mail_port' => $this->request->mail_port ?? "465",
            'mail_password' => $this->request->mail_password,
            'mail_from_address' => $this->request->mail_from_address ?? null,
            'mail_from_name' => $this->request->mail_from_name ?? "Jhigu",
            'mail_encryption' => $this->request->mail_encryption ?? "ssl",
            'is_default' => $this->request->is_default ?? false,
        ];
        $sMTP->update($data);
        if ($this->request->has('is_default')) {
            SMTP::where('id', '!=', $sMTP->id)->update([
                'is_default'=>false,
            ]);
            foreach($data as $key=>$value){
                $this->putPermanentEnv(strtoupper($key), $value);
            }
        }
    }

    private function putPermanentEnv($key, $value)
    {
        if($key == "MAIL_USER_NAME"){
            $key = "MAIL_USERNAME";
        }
        file_put_contents(app()->environmentFilePath(), str_replace(
            $key . '=' . env($key),
            $key . '=' . '"'.$value.'"',
            file_get_contents(app()->environmentFilePath())
        ));

    }
}
