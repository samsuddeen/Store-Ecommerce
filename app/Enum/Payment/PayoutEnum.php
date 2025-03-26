<?php
namespace App\Enum\Payment;

use App\Abstract\Enum;

class PayoutEnum extends Enum
{
    const PENDING=0;
    const NOT_RECEIVED = 1;
    const RECEIVED = 2;
    const REQUESTED = 3;
    const PROCESSING = 4;
    const APPROVED = 5;
    const CANCEL=6;
    const REJECTED = 7;
}