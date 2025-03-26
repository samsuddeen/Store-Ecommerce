<?php
namespace App\Data\SellerReturnOrders;
use App\Models\Seller;
class SellerReturnOrderData
{
    protected $seller;
    public function __construct(Seller $seller)
    {
        $this->seller=$seller;
    }

    public function getData()
    {
        dd($this->seller);
    }
}


