<?php

namespace App\Enum\Order;

use App\Abstract\Enum;



class OrderStatusEnum extends Enum
{
    const SEEN = 1;
    const READY_TO_SHIP = 2;
    const DISPATCHED = 3;
    const SHPIED = 4;
    const DELIVERED = 5;
    const CANCELED = 6;
    const REJECTED = 7;
}