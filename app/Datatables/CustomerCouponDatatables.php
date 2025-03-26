<?php

namespace App\Datatables;

use App\Helpers\Utilities;
use App\Models\Admin\Coupon\Customer\CustomerCoupon;
use Yajra\DataTables\Facades\DataTables;

class CustomerCouponDatatables implements DatatablesInterface
{

    public function getData()
    {
        $data = CustomerCoupon::latest();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('discount', function ($row) {
                // This must be changed according to the currency after currency added
                if($row->coupon->is_percentage == 'yes'){
                    $discount = $row->coupon->discount.' %';
                }else{
                    $discount = $row->coupon->discount.' $';
                }
                return $discount;
            })
            ->addColumn('code', function ($row) {
                return $row->coupon->coupon_code;
            })
            ->addColumn('action', function ($row) {
                $edit =  Utilities::button(href: route('coupon.edit', $row->id), icon: "edit", color: "primary", title: 'Edit Coupon');
                $show = Utilities::delete(href: route('coupon.destroy', $row->id), id: $row->id);
                return  $edit . '' . $show;
            })
            ->rawColumns(['action', 'discount', 'code'])
            ->make(true);
    }
}
