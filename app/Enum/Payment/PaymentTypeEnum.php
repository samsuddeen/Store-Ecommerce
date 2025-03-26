<?php
namespace App\Enum\Payment;

use App\Abstract\Enum;

class PaymentTypeEnum extends Enum
{
    const WALET=1;
    const BANK=2;
    const CASH=3;
}