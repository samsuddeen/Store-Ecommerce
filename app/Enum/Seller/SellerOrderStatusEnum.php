<?php

namespace App\Enum\Seller;
use App\Abstract\Enum;

class SellerOrderStatusEnum Extends Enum
{
    const SEEN = 1 ;
    const READY_TO_SHIP = 2 ;
    const DISPATCHED = 3 ;
    const SHIPED = 4;
    const DELIVERED = 5;
    const DELIVERED_TO_HUB = 6;
    const CANCELED = 7;
    const REJECTED = 8;
}