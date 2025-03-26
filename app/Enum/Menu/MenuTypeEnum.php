<?php
namespace App\Enum\Menu;

use App\Abstract\Enum;

class MenuTypeEnum extends Enum
{
    const CATEGORY=1;
    const TAG=2;
    const PAGE=3;
    const SELLER=4;
    const BRAND=5;
    const PRODUCT=6;
    const EXTERNAL_LINK=7;
}