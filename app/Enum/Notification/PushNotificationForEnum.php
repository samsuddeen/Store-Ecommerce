<?php
namespace App\Enum\Notification;

use App\Abstract\Enum;

class PushNotificationForEnum extends Enum
{
    const ALL=1;
    const SELECTED=2;
    // const SINGLE=3;
    // const Area = 6;
    const Email = 7;
    const Phone = 8;
}