<?php
namespace App\Data\Seller;

class SellerProductBillBarCode{

    protected $seller_order;
    public function __construct($seller_order)
    {
        $this->seller_order=$seller_order;


    }

    public function generateBill()
    {
        dd($this->seller_order);
    }




}