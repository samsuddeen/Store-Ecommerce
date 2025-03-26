<?php
namespace App\Enum\MessageSetup;

use App\Abstract\Enum;

class MessageSetupEnum extends Enum
{
    const ORDER_PLACE = 1;
    const CHECKOUT = 2;
    const DELIVERY = 3;
    const ORDER_STATUS_CHANGED = 4;
    const PAYOUT_REQUEST = 5;
    const PAYOUT_REQUEST_STATUS_CHANGED = 6;
    const REFUND_REQUEST = 7;
    const REFUND_REQUEST_STATUS_CHANGED = 8;
    const ORDER_READY_TO_SHIP=9;
    const DISPATCHED=10;
    const SHIPED=11;
    const DELIVERED=12;
    const CANCEL=13;
    const REJECTED=14;
}