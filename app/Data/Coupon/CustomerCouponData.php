<?php
namespace App\Data\Coupon;

use App\Models\Admin\Coupon\Customer\CustomerCoupon;

class CustomerCouponData
{
    protected $filters;
    protected $data=[];
    function __construct($filters)
    {
        $this->filters = $filters;
    }
    public function getData()
    {
        $this->initializeData();
        return $this->data;
    }
    private function initializeData()
    {
        $coupons = CustomerCoupon::orderBy('created_at', 'desc')->get();
        $this->data = $coupons;
    }
}