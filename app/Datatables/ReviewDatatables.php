<?php

namespace App\Datatables;

use App\Helpers\Utilities;
use App\Models\Review;
use Yajra\DataTables\Facades\DataTables;

class ReviewDatatables implements DatatablesInterface
{

    public function getData()
    {
        $data = Review::latest();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('customer',function ($row){
                return $row->user->name;
            })
            ->addColumn('product',function ($row){
                return $row->product->name;
            })
            ->addColumn('seller',function ($row){
                return $row->product->user->name;
            })
            ->addColumn('comment',function ($row){
                return $row->message;
            })         
            ->addColumn('action', function ($row) {
                $delete = Utilities::delete(href: route('review.delete', $row->id), id: $row->id);
                return  $delete;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
