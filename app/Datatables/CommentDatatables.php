<?php
namespace App\Datatables;

use App\Models\Order;
use App\Models\Seller;
use App\Helpers\Utilities;
use Illuminate\Support\Str;
use App\Models\QuestionAnswer;
use App\Models\Order\Seller\SellerOrder;
use Yajra\DataTables\Facades\DataTables;

class CommentDatatables{

    private $datatable;
    public function __construct()
    {
       
    }

    public function getAllComment()
    {
        $product=QuestionAnswer::orderBy('created_at','DESC')->get();
        // $comment=collect($product)->whereNull('parent_id')->where('status','0');
        $comment=collect($product)->whereNull('parent_id');
        
        $data=$comment;
        return DataTables::of($data)
        ->addIndexColumn()
        ->addColumn('all_action', function ($row) {
            return '<input type="checkbox"/>';
        })
        ->addColumn('status', function ($row) {
            $status = '';
            if($row->status == 0)
            {
                $status = '<div class="d-flex"><span class="badge bg-primary pending-status">InActive</span></div>';
            }
            else
            {
                $status = $this->getStatus($row->status);
            }
            $action = '<div class="d-flex">' . $status . '<div class="dropdown"><button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0" data-bs-toggle="dropdown"><i data-feather="more-vertical"></i></button><div class="dropdown-menu dropdown-menu-end">';

            $action .=$this->getActions($row);

            $action .= '</div></div></div>';
            return $action;
        })
        ->addColumn('product_name', function ($row) {
            
            return Str::limit($row->product->name ?? null, 10);
        })
        ->addColumn('product_image', function ($row) {
            if($row->product)
            {
                $image_url=completedOrderImage($row->product->productImages) ?? asset('kalafurniture.jpg');
            }
            else
            {
                $image_url=asset('kalafurniture.jpg');
            }
           
            $image='';
            $image='<img src="'.$image_url.'" height="100" width="100">';
            return $image;
        })
        ->addColumn('received_on', function ($row) {
            return $row->created_at;
        })
        ->addColumn('action', function ($row) {
            $show='';
            $show='<a href="javascript:;" type="button" class="btn btn-primary success-status" data-bs-toggle="modal" data-bs-target="#viewComment'.$row->id.'">View Message</a>';
            // $show =  Utilities::button(href: route('admin.viewOrder', $row->id ?? 0), icon: "eye", color: "info", title: 'Show Order');
            return  $show;
        })
        ->rawColumns(['received_on','product_image','product_name','status','action'])
        ->make(true);
    }

    public function getStatus($status)
    {
        $return_status = '';
        switch ($status) {
            case 0:
                $return_status =  '<div class="badge bg-info">Inactive</div>';
                break;
            case 1:
                $return_status =  '<div class="badge bg-primary">Active</div>';
                break;
            default:
                # code...
                break;
        }
        return $return_status;
    }
    private function getActions($row)
    {

        $action = '';
        switch ($row->status) {
            case 0:
                $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="active" data-order_id="'.$row->id.'" href="#"><i data-feather="pie-chart" class="me-50"></i><span>Active</span></a>';
                break;
            case 1:
                $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="inactive" data-order_id="'.$row->id.'" href="#"><i data-feather="truck" class="me-50"></i><span>InActive</span></a>';
                break;
          
            default:
                # code...
                break;
        }
        return $action;
    }

   
}