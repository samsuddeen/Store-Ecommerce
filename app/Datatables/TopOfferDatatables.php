<?php

namespace App\Datatables;

use App\Helpers\Utilities;
use App\Models\Admin\Offer\TopOffer;
use App\Models\Admin\Product\Featured\FeaturedSection;
use Yajra\DataTables\Facades\DataTables;

class TopOfferDatatables implements DatatablesInterface
{

    public function getData()
    {
        $data = TopOffer::latest();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $edit =  Utilities::button(href: route('top-offer.edit', $row->id), icon: "edit", color: "primary", title: 'Edit location');
                $show = Utilities::delete(href: route('top-offer.destroy', $row->id), id: $row->id);
                return  $edit . '' . $show;
            })
            ->addColumn('offer', function ($row) {
                $offer =  ($row->is_fixed) ? $row->offer : $row->offer.' %';
                return $offer;
            })
            // ->addColumn('count', function ($row) {
            //     $show = '<a href="#"/>'.$row->count.'</a>';
            //     return $show;
            // })
            ->addColumn('total', function ($row) {
                $show =  Utilities::button(href: route('top-offer-product.index', $row->id), icon: "eye", color: "info", title: 'Show');
                $add = '<a href="'.route('top-offer-product.create', $row->id).'"> Add </a>';
                return $row->offerProducts->count().' '.$show.''.$add;
            })
            ->rawColumns(['action', 'offer', 'total'])
            ->make(true);
    }
}
