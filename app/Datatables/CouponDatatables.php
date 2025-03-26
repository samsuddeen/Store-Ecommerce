<?php

namespace App\Datatables;

use App\Helpers\Utilities;
use App\Models\Coupon;
use App\Models\Location;
use Yajra\DataTables\Facades\DataTables;

class CouponDatatables implements DatatablesInterface
{

    public function getData()
    {
        $data = Coupon::latest();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('discount', function ($row) {

                // This must be changed according to the currency after currency added
                if($row->is_percentage == 'yes'){
                    $discount = $row->discount.' %';
                }else{
                    $discount = $row->discount.' $';
                }
                return $discount;
            })
            ->addColumn('code', function ($row) {
                return $row->coupon_code;
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
