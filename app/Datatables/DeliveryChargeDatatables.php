<?php

namespace App\Datatables;

use App\Helpers\Utilities;
use App\Models\DeliveryCharge;
use Yajra\DataTables\Facades\DataTables;

class DeliveryChargeDatatables implements DatatablesInterface
{

    public function getData()
    {
        $data = DeliveryCharge::latest();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('from', function($row){
                $from = $row->delivery_route;
                return $from->location_from->local_name;
                // return $from->location_from->title;
            })
            ->addColumn('to', function($row){
                $to = $row->delivery_route;
                return $to->location_to->local_name;
                // return $to->lacation_to->title;
            })
            ->addColumn('Branch Delivery ($)', function($row){
                $price = $row->branch_delivery;
                return $row->currency." ".$price;
            })
            ->addColumn('Door Delivery ($)', function($row){
                $price = $row->door_delivery;
                return $row->currency." ".$price;

            })
            ->addColumn('Branch Express Delivery ($)', function($row){
                $price = $row->branch_express_delivery;
                return $row->currency." ".$price;

            })
            ->addColumn('Branch Normal Delivery ($)', function($row){
                $price = $row->branch_normal_delivery;
                return $row->currency." ".$price;

            })
            ->addColumn('Door Express Delivery ($)', function($row){
                $price = $row->door_express_delivery;
                return $row->currency." ".$price;

            })
            ->addColumn('Door Normal Delivery ($)', function($row){
                $price = $row->door_normal_delivery;
                return $row->currency." ".$price;

            })
            ->addColumn('action', function ($row) {
                $edit =  Utilities::button(href: route('delivery-charge.edit', $row->id), icon: "edit", color: "primary", title: 'Edit Delivery Charge');
                $show = Utilities::delete(href: route('delivery-charge.destroy', $row->id), id: $row->id);
                return  $edit . '' . $show;
            })
            ->rawColumns(
                [
                    'action',
                    'from',
                    'to',
                    'Branch Delivery ($)',
                    'Door Delivery ($)',
                    'Branch Express Delivery ($)',
                    'Branch Normal Delivery ($)',
                    'Door Express Delivery ($)',
                    'Door Normal Delivery ($)',
                ]
                )
            ->make(true);
    }
}
