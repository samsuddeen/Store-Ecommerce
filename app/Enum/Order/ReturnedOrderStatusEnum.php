<?php
namespace App\Enum\Order;

use App\Abstract\Enum;



class ReturnedOrderStatusEnum extends Enum
{
    const PENDING = 1;
    const APPROVED = 2;
    const RETURNED = 3;
    const REJECTED = 4;
    const RECEIVED = 5;
}