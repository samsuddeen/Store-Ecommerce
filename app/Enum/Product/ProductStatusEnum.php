<?php

namespace App\Enum\Product;


use App\Abstract\Enum;

class ProductStatusEnum extends Enum
{
    const ACTIVE = 1;
    const SUSPEND = 2;
    const BLOCKED = 3;
    const INACTIVE = 4;
}
