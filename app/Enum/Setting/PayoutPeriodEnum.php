<?php
namespace App\Enum\Setting;

use App\Abstract\Enum;

class PayoutPeriodEnum extends Enum
{
    const DAILY=1;
    const WEEKLY=2;
    const MONTHLY=3;
    const QUATERLY=4;
    const HALFLY=5;
    const YEARLY=6;
}