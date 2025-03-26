<?php

namespace App\Http\Controllers\Report;

use App\Models\seller as Seller;
use App\Models\Product;
use App\Data\Date\DateData;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Exports\ProductExport;
use App\Data\Filter\FilterData;
use App\Data\Product\ProductData;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Datatables\ProductDatatables;
use Yajra\DataTables\Facades\DataTables;


class ProductReportController extends Controller
{
    protected $datatable;

    public function index(Request $request)
    {
        $data['filters'] = (new FilterData($request))->getData();
        $dateData = new DateData();
        $data['months'] = $dateData->getMonths();
        $data['years'] = $dateData->getYears();
        $data['seller'] = Seller::get();
        if ($request->ajax()) {
            $filter = (new FilterData($request))->getData();
            if (!Arr::get($filter, 'type')) {
                $filters['type'] = null;
            }
            $filters = (new FilterData($request))->getData();

            $datatables = (new ProductDatatables($filters))->getData();
            $this->datatable = $datatables;       
            
            return $this->datatable->getData();
        }
        // dd($data);
        $request->session()->put('admin_product_report',$data);
        return view('report.product-report.index', $data);
    }

    public function exportProduct()
    {
        return Excel::download(new ProductExport(), 'Product.CSV');
    }
}
