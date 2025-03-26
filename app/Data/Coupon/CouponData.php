<?php
namespace App\Data\Coupon;

use App\Models\Coupon;
use Carbon\Carbon;

class CouponData
{
    protected $filters;
    function __construct($filters=null)
    {
        $this->filters = $filters;        
    }
    public function getCoupons()
    {
        $date = Carbon::now()->toDateString();
        $coupons = Coupon::whereDate('from', '<=', $date)->whereDate('to', '>=', $date)->where('publishStatus',1)->orderBy('title')->get();
        return $coupons;
    }
}