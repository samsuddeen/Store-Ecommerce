<?php
namespace App\Data\Payment;

use App\Models\Payment\PaymentMethod;

class PaymentMethodData
{
    protected $filters;
    function __construct($filters=null)
    {
        $this->filters = $filters;
    }
    public function getData()
    {
        $payment_methods = PaymentMethod::latest()->get();
        return $payment_methods;
    }
    public function getSingleMethod($id)
    {
        return PaymentMethod::findOrFail($id);
    }
}