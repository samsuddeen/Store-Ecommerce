<?php
namespace App\Enum\Order;

use App\Abstract\Enum;

class RefundStatusEnum extends Enum
{
    const PENDING=1;
    const PAID=2;
    const REJECTED=3;
}