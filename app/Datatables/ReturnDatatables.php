<?php

namespace App\Datatables;

use App\Helpers\Utilities;
use App\Models\Product;
use App\Models\QuestionAnswer;
use App\Models\ReturnProduct;
use Yajra\DataTables\Facades\DataTables;
use Spatie\Permission\Traits\HasRoles;

class   ReturnDatatables implements DatatablesInterface
{
    use HasRoles;
    public function getData()
    {
        if (auth()->user()->hasRole('super admin')) {
            $data = ReturnProduct::latest();
        }else{
            $data = auth()->user()->product_return;
        }
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('product',function($row){
                return $row->product->name;
            })  
            ->addColumn('customer',function($row){
                return $row->user->name;
            })        
            ->addColumn('action', function ($row) {
                $edit =  Utilities::button(href: route('product.edit', $row->id), icon: "edit", color: "primary", title: 'Edit Product');
                $show = Utilities::button(href: route('product.show', $row->id), icon: "eye", color: "primary", title: 'Show Product');
                $delete = Utilities::delete(href: route('location.destroy', $row->id), id: $row->id);
                $status =  Utilities::button(href: route('updateStatus', $row->id), icon: "edit", color: "success", title: 'status Product');



                return  $edit . '' . $show. ''. $delete. ''. $status;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
