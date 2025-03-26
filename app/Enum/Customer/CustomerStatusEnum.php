<?php

namespace App\Enum\Customer;

use App\Abstract\Enum;

class CustomerStatusEnum extends Enum
{

    const Active = 1;
    const Inactive = 2;
    const Blocked = 3;
}
