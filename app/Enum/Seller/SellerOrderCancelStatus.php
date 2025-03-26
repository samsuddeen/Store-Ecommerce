<?php

namespace App\Enum\Seller;
use App\Abstract\Enum;

class SellerOrderCancelStatus Extends Enum
{
    const PENDING= '0' ;
    const APPROVED = '1' ;
    const REJECTED = '2' ;
}