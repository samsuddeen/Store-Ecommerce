<?php
namespace App\Enum\Reason;

use App\Abstract\Enum;

class ReasonEnum extends Enum
{
    const ORDER = "App\Models\Order";
    const PAYOUT="App\Models\Payout\Payout";
    const REFUND = "App\Models\Refund\Refund";
}