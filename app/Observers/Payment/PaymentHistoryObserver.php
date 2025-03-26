<?php
namespace App\Observers\Payment;

use App\Models\Payment\PaymentHistory;

class PaymentHistoryObserver
{
    protected $data;
    function __construct($data)
    {
        $this->data = $data;
    }
    public function observe()
    {
        PaymentHistory::create($this->data);
    }
}