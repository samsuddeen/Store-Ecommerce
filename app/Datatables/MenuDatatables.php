<?php

namespace App\Datatables;

use App\Enum\Menu\MenuShowOn;
use App\Enum\Menu\MenuTypeEnum;
use App\Helpers\Utilities;
use App\Models\Category;
use App\Models\Menu;
use Yajra\DataTables\Facades\DataTables;

class MenuDatatables implements DatatablesInterface
{

    public function getData()
    {
        $data = Menu::withCount('children')->orderBy('position', 'asc')->get();
        return DataTables::of($data)
            ->addIndexColumn()
            // ->setRowId(function ($row) {
            //     return $row->id; // Assuming that 'id' is the actual column name in your Menu model
            // })
            ->addColumn('total_children', function ($row) {
                return $row->children_count;
            })
            ->addColumn('image', function ($row) {
                return Utilities::image($row->banner_image ?? asset('dummyimage.png')) ;
            })

            ->addColumn('type', function ($row) {
                return $this->getType($row);
            })
           
            ->addColumn('show_on', function ($row) {
                return $this->getShowOn($row);
            })
            ->addColumn('status', function ($row) {
                $action = '';
                $action .= '<span class="text-';
                $action .= ($row->status == 1) ? 'success"' : 'danger"';
                $action .= '>';
                $action .= ($row->status == 1) ? 'Active' : 'Inactive';
                $action .= '</span>';
                return $action;
            })
            ->addColumn('action', function ($row) {
                $edit =  Utilities::button(href: route('menu.edit', $row->id), icon: "edit", color: "primary", title: 'Edit Meni');
                $delete = $row->children_count ?
                    null :
                    Utilities::delete(href: route('menu.destroy', $row->id), id: $row->id);
                return  $edit . '' . $delete;
            })
            ->setRowAttr([
                'data-id' => function ($row) {
                    return $row->id;
                },
            ])
            ->rawColumns(['action', 'image', 'total_children', 'show_on', 'type',  'status'])
            ->make(true);
    }


    private function getType(Menu $menu)
    {
        $type = "";
        switch ($menu->menu_type) {
            case MenuTypeEnum::CATEGORY:
                $type = '<div class="badge bg-primary">Category</div>';
                break;
            case MenuTypeEnum::TAG:
                $type = '<div class="badge bg-secondary">Tag</div>';
                break;
            case MenuTypeEnum::PAGE:
                $type = '<div class="badge bg-info">Page</div>';
                break;
            case MenuTypeEnum::SELLER:
                $type = '<div class="badge bg-success">Seller</div>';
                break;
            case MenuTypeEnum::BRAND:
                $type = '<div class="badge bg-primary">Brand</div>';
                break;
            case MenuTypeEnum::PRODUCT:
                $type = '<div class="badge bg-warning">Product</div>';
                break;
            case MenuTypeEnum::EXTERNAL_LINK:
                $type = '<div class="badge bg-danger">External Link</div>';
                break;
            default:
                # code...
                break;
        }
        return $type;
    }
    private function getShowOn(Menu $menu)
    {
        $type = "";
        switch ($menu->menu_type) {
            case MenuShowOn::MAIN:
                $type = '<div class="badge bg-primary">Main Menu</div>';
                break;
            case MenuShowOn::TOP:
                $type = '<div class="badge bg-secondary">Top</div>';
                break;
            case MenuShowOn::FOOTER:
                $type = '<div class="badge bg-info">Footer</div>';
                break;
            case MenuShowOn::MAIN_AND_TOP:
                $type = '<div class="badge bg-success">Main and Top</div>';
                break;
            case MenuShowOn::MAIN_AND_FOOTER:
                $type = '<div class="badge bg-primary">Main and Footer</div>';
                break;
            case MenuShowOn::TOP_AND_FOOTER:
                $type = '<div class="badge bg-warning">Top and footer</div>';
                break;
            case MenuShowOn::ALL:
                $type = '<div class="badge bg-danger">All</div>';
                break;
            default:
                # code...
                break;
        }
        return $type;
    }
}
