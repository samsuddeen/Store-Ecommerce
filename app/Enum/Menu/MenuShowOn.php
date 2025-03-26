<?php
namespace App\Enum\Menu;

use App\Abstract\Enum;

class MenuShowOn extends Enum
{
    const MAIN=1;
    const TOP=2;
    const FOOTER=3;
    const MAIN_AND_TOP=4;
    const MAIN_AND_FOOTER=5;
    const TOP_AND_FOOTER = 6;
    const ALL=7;
}