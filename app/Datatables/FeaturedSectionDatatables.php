<?php

namespace App\Datatables;

use App\Enum\Featured\FeaturedSectionEnum;
use App\Helpers\Utilities;
use App\Models\Admin\Product\Featured\FeaturedSection;
use Yajra\DataTables\Facades\DataTables;

class FeaturedSectionDatatables implements DatatablesInterface
{

    public function getData()
    {
        $data = FeaturedSection::latest();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $edit =  Utilities::button(href: route('featured-section.edit', $row->id), icon: "edit", color: "primary", title: 'Edit location');
                $show = Utilities::delete(href: route('featured-section.destroy', $row->id), id: $row->id);
                return  $edit . '' . $show;
            })
            ->addColumn('total', function ($row) {
                $type = (new FeaturedSectionEnum)->getSingleValue($row->type);
                // $show =  Utilities::button(href: route('featured-section-product.index', $row->id), icon: "eye", color: "info", title: 'Show');
                $add = '<a href="'.route('featured-product.create', [$type,$row->id]).'">Add </a>';
                // return $row->featured->count().' '.$show.''.$add;
                return $row->featured->count().' '.$add;
            })
            ->rawColumns(['action', 'count', 'total'])
            ->make(true);
    }
}
