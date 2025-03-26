<?php
namespace App\Mail;

use App\Models\SMTP\SMTP;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GetInquiryMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    /**
     * Create a new message instance.
     *
     * @param  array  $data
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $fromAddress = SMTP::where('is_default', 1)->first();
        $from = [
            'address' => $fromAddress->mail_from_address,
            'name' => $fromAddress->mail_from_name,
        ];
        
        return $this->from($from['address'], $from['name'])
            ->view('email.inquirygat')
            ->with('data', $this->data);
    }
}
