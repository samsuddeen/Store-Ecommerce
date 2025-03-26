<?php

namespace App\Http\Controllers\Datatables;

use App\Data\Filter\FilterData;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\QuestionAnswer;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Datatables\ProductDatatables;

class ProductController extends Controller
{
    private $datatable;
    /**
     * Display a listing of the resource.
     *P
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filters = (new FilterData($request))->getData();
        $datatables = new ProductDatatables($filters);
        $this->datatable = $datatables;
        return   $this->datatable->getData();
    }

    public function updatestatus($id)
    {
        $datas = QuestionAnswer::find($id);

        if ($datas->status == 1) {
            $status = 0;
        } else {
            $status = 1;
        }
        $value = array('status' => $status);
        DB::table('question_answers')->where('id', $id)->update($value);
        return redirect('admin/product');
    }
}
