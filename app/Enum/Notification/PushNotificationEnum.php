<?php
namespace App\Enum\Notification;

use App\Abstract\Enum;

class PushNotificationEnum extends Enum
{
    const PUSHED = 1;
    const NOT_PUSHED = 2;
    const DRAFT = 3;
}