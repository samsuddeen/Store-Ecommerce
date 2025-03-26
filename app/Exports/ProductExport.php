<?php

namespace App\Exports;

use App\Models\Product;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ProductExport implements FromView
{
    public function view():View
    {        
        $product = session('admin_product_report');   
        $product=$product['seller'];
        return view('admin.Excel.productexcel',compact('product'));
    }
}