<?php

namespace App\Data\Product\Seller;

use App\Models\Product;
use App\Helpers\Utilities;
use App\Helpers\ProductFormHelper;
use App\Enum\Product\ProductStatusEnum;
use Yajra\DataTables\Facades\DataTables;

class SellerProductData
{
    protected $filters;
    protected $data;
    function __construct($filters = [])
    {
        $this->filters = $filters;
        $this->initializeData();
    }
    public function initializeData()
    {
        $this->data = Product::Visible()->latest();
    }
    public function getData()
    {
        return DataTables::of($this->data)
            ->addIndexColumn()
            ->addColumn('name_element', function ($row) {
                return $this->getName($row);
            })
            ->addColumn('category', function ($row) {
                return $this->getCategory($row);
            })
            ->addColumn('stock', function ($row) {
                return $this->stock($row);
            })
            ->addColumn('publish', function ($row) {
                return $this->getPublish($row);
            })
            ->addColumn('status', function ($row) {
                $status = '';
                if ((int)$row->pending == 1) {
                    $status = '<div class="d-flex"><span class="badge bg-primary">Pending</span><span class="badge bg-warning">New</span></div>';
                } else {
                    $status = $this->getStatus($row->status);
                }
                $action = '<div class="d-flex">' . $status . '<div class="dropdown"><button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0" data-bs-toggle="dropdown"><i data-feather="more-vertical"></i></button><div class="dropdown-menu dropdown-menu-end">';

                $action .= $this->getActions($row);

                $action .= '</div></div></div>';
                return $action;
            })
            ->addColumn('action', function ($row) {
                $edit =  Utilities::button(href: route('seller-product.edit', $row->id), icon: "edit", color: "primary", title: 'Edit Product');
                $show = Utilities::button(href: route('seller-product.show', $row->id), icon: "eye", color: "primary", title: 'Show Product');
                $delete = Utilities::delete(href: route('seller-product.destroy', $row->id), id: $row->id);
                return  $edit . '' . $show . '' . $delete;
            })
            ->rawColumns(['name_element', 'action', 'publish', 'status', 'stock', 'category'])
            ->make(true);
    }
    private function getPublish(Product $product)
    {
        $retun_string = "";
        if ($product->publishStatus) {
            $retun_string = "<span class='badge bg-primary'>Yes</span>";
        } else {
            $retun_string = "<span class='badge bg-danger'>No</span>";
        }
        return $retun_string;
    }

    public function getStatus($status)
    {
        $return_status = '';
        switch ($status) {
            case ProductStatusEnum::ACTIVE:
                $return_status =  '<div class="badge bg-primary">ACTIVE</div>';
                break;
            case ProductStatusEnum::SUSPEND:
                $return_status =  '<div class="badge bg-danger">SUSPEND</div>';
                break;
            case ProductStatusEnum::BLOCKED:
                $return_status =  '<div class="badge bg-warning">BLOCKED</div>';
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
            case ProductStatusEnum::ACTIVE:
                $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="2" data-product_id="' . $row->id . '" href="#"><i data-feather="pie-chart" class="me-50"></i><span>Suspend</span></a>';
                $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="3" data-product_id="' . $row->id . '" href="#"><i data-feather="truck" class="me-50"></i><span>Block</span></a>';
                break;
            case ProductStatusEnum::SUSPEND:
                $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="1" data-product_id="' . $row->id . '" href="#"><i data-feather="truck" class="me-50"></i><span>Active</span></a>';
                break;
            case ProductStatusEnum::BLOCKED:
                $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="1" data-product_id="' . $row->id . '" href="#"><i data-feather="truck" class="me-50"></i><span>Active</span></a>';
                break;
            default:
                # code...
                break;
        }
        return $action;
    }


    private function stock(Product $product)
    {
        $qty = 0;
        foreach ($product->stocks ?? [] as $stock) {
            $qty = $qty + $stock->quantity;
        }
        return $qty;
    }
    private function getName(Product $product)
    {
        $retun_string = '';
        $featured_image = ProductFormHelper::getFeaturedImage($product);
        $retun_string = "<div>";
        $retun_string .= "<img src='$featured_image' alt='Image Not Found' class='img-fluid'>";
        $retun_string .= '<a href="' . route('product.show', $product->id) . '">' . substr($product->name, 0, 10) . '..........</a>';
        $retun_string .= "</div>";
        return $retun_string;
    }
    private function getCategory(Product $product)
    {
        $retun_string = '<a href="' . route('category.show', $product->category->id ?? 0) . '">' . substr($product->category->title, 0, 10) . '..........</a>';
        return $retun_string;
    }
}
