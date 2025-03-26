<?php

namespace App\Mail;

use App\Models\OrderAsset;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Order;

class CustomerOrderMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    protected $order = null;
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $order_asset = $this->order->orderAssets;
        return $this->view('frontend.emails.customer.customerordermail')
            ->with('order', $this->order)
            ->with('order_asset', $order_asset);
    }
}
