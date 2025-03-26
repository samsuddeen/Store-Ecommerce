<?php

namespace App\Datatables;

use App\Models\Order;
use App\Helpers\Utilities;
use App\Models\Order\Seller\SellerOrder;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Seller;

class OrderDatatables implements DatatablesInterface
{
    protected $filters;
    function __construct($filters = [])
    {
        $this->filters = $filters;
    }
    public function getData()
    {
        $data = Order::latest();
        return DataTables::of($data)
        
            ->addIndexColumn()
            ->addColumn('all_action', function ($row) {
                // dd($this->filters);
                return '<input type="checkbox"/>';
            })
            // ->addColumn('seller_value', function ($row) {
            //     $seller_data='';
            //     foreach(orderSeller($row) as $sellerData)
            //     {
            //         $seller_data.= $sellerData;
            //     }
            //     return $seller_data;
            // })
            ->addColumn('total_discount', function ($row) {
                
                return formattedNepaliNumber($row->total_discount);
            })
            ->addColumn('total_price', function ($row) {
                
                return formattedNepaliNumber($row->total_price);
            })
            ->addColumn('order_by', function ($row) {
                if($row->user->wholeseller=='1')
                {
                    return "<a href='#'>" . $row->user->name."(WholeSeller)" ?? ''. "[" . $row->user->phone . "]</a>";
                }
                return "<a href='#'>" . $row->user->name ?? '' . "[" . $row->user->phone . "]</a>";
            })
            ->addColumn('payment_status', function ($row) {
                return (int)$row->payment_status == 1 ? 'Paid' : 'Not Paid';
            })
            ->addColumn('status', function ($row) {
               
                $status = '';
                $cancelOrder=$row->orderAssets;
                $cancelOrder=collect($cancelOrder)->filter(function($item)
                {
                    if($item->cancel_status=='0')
                    {
                        return $item;
                    }
                });
                if(count($cancelOrder) >0)
                {
                    if ((int)$row->pending == 1) {
                        $status = '<div class="d-flex"><span class="badge btn-secondary">Pending</span><span class="badge bg-warning success-status">New</span></div>';
                    } else {
                        $status = $this->getStatus($row->status);
                    }
                    $action = '<div class="d-flex">' . $status . '<div class="dropdown"><button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0" data-bs-toggle="dropdown"><i data-feather="more-vertical"></i></button><div class="dropdown-menu dropdown-menu-end">';
    
                    $action .= $this->getActions($row);
    
                    $action .= '</div></div></div>';
                    
                }
                else
                {
                    if($row->status==6 || $row->status==7)
                    {
                        $status = $this->getStatus($row->status);
                        $action = '<div class="d-flex">' . $status . '<div class="dropdown"><button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0" data-bs-toggle="dropdown"><i data-feather="more-vertical"></i></button><div class="dropdown-menu dropdown-menu-end">';
    
                        $action .= $this->getActions($row);
        
                        $action .= '</div></div></div>';
                    }
                    else
                    {
                        $status = $this->getStatus(8);
                        $action = '<div class="d-flex">' . $status . '<div class="dropdown"><button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0" data-bs-toggle="dropdown"><i data-feather="more-vertical"></i></button><div class="dropdown-menu dropdown-menu-end">';
                        $action .= $this->getCancelActions($row);
                        $action .= '</div></div></div>';
                    }
                    
                    
                }
                
                return $action;
            })
            ->addColumn('ref_id', function ($row) {
                $show =  '<a href="' . route('admin.viewOrder', $row->ref_id ?? 0) . '">' . $row->ref_id . '</a>';
                return  $show;
            })
            ->addColumn('action', function ($row) {

                $show='';
                if($row->status==='4'){
                    $show.='<div class="btn-group"><a href="javascript:;" data-orderid="'.$row->id.'" class="btn btn-sm btn-primary assignTask">Assign Delivery</a>';
                    $show.='<div class="btn-group"><a href="javascript:;" data-orderId="'.$row->id.'" class="btn btn-sm btn-danger barcode_btn" >View</a>';
                }
                elseif($row->status==='5')
                {
                    $show.='<div class="btn-group"><a href="javascript:;" data-orderId="'.$row->id.'" class="btn btn-sm btn-danger barcode_btn" >View</a>';

                }elseif($row->status==='6')
                {
                    $show.='<div class="btn-group"><a href="javascript:;" data-orderId="'.$row->id.'" class="btn btn-sm btn-primary cancelReason" data-bs-toggle="modal" data-bs-target="#reasonCancel">View Reason</a>';
                }

                // $show.=  Utilities::button(href: route('seller-order.show', $row->id ?? 0), icon: "eye", color: "info", title: 'Show Order');
                // $show.="</div>";
                // return  $show;

                $show .=  Utilities::button(href: route('admin.viewOrder', $row->ref_id ?? 0), icon: "eye", color: "info success-status", title: 'Show Order');
                // dd($show);
                return  $show;
            })
            ->rawColumns(['status', 'order_by', 'payment_status', 'all_action', 'action', 'ref_id','total_discount','total_price','seller_value'])
            ->make(true);
    }
    public function getStatus($status)
    {
        $return_status = '';
        switch ($status) {
            case 1:
                $return_status =  '<div class="badge bg-primary success-status">SEEN</div>';
                break;
            case 2:
                $return_status =  '<div class="badge bg-info">READY_TO_SHIP</div>';
                break;
            case 3:
                $return_status =  '<div class="badge bg-info process-status">DISPATCHED</div>';
                break;
            case 4:
                $return_status =  '<div class="badge bg-warning reject-status">SHIPPED</div>';
                break;
            case 5:
                $return_status =  '<div class="badge bg-success success-status">DELIVERED</div>';
                break;
            case 6:
                $return_status =  '<div class="badge bg-danger pending-status">CANCELED</div>';
                break;
            case 7:
                $return_status =  '<div class="badge bg-danger pending-status">REJECTED</div>';
                break;
                case 8:
                    $return_status =  '<div class="badge bg-danger pending-status">CANCELLED ON REQUEST</div>';
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
            case 1:
                $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="ready_to_ship" data-order_id="' . $row->id . '" href="#"><i data-feather="pie-chart" class="me-50"></i><span>Redy To Ship</span></a>';
                $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="dispatched" data-order_id="' . $row->id . '" href="#"><i data-feather="truck" class="me-50"></i><span>Dispatched</span></a>';
                $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="shiped" data-order_id="' . $row->id . '" href="#"><i data-feather="truck" class="me-50"></i><span>Shipped</span></a>';
                $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="delivered" data-order_id="' . $row->id . '" href="#"><i data-feather="target" class="me-50"></i><span>Delivered</span></a>';
                $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="cancel" data-order_id="' . $row->id . '" href="#"><i data-feather="alert-octagon" class="me-50"></i><span>Canecl</span></a>';
                $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="reject" data-order_id="' . $row->id . '" href="#"><i data-feather="crosshair" class="me-50"></i><span>Reject</span></a>';
                break;
            case 2:
                $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="dispatched" data-order_id="' . $row->id . '" href="#"><i data-feather="truck" class="me-50"></i><span>Dispatched</span></a>';
                $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="shiped" data-order_id="' . $row->id . '" href="#"><i data-feather="truck" class="me-50"></i><span>Shipped</span></a>';
                $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="delivered" data-order_id="' . $row->id . '" href="#"><i data-feather="target" class="me-50"></i><span>Delivered</span></a>';
                $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="cancel" data-order_id="' . $row->id . '" href="#"><i data-feather="alert-octagon" class="me-50"></i><span>Canecl</span></a>';
                $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="reject" data-order_id="' . $row->id . '" href="#"><i data-feather="crosshair" class="me-50"></i><span>Reject</span></a>';
                break;
            case 3:
                $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="shiped" data-order_id="' . $row->id . '" href="#"><i data-feather="truck" class="me-50"></i><span>Shiped</span></a>';
                $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="delivered" data-order_id="' . $row->id . '" href="#"><i data-feather="target" class="me-50"></i><span>Delivered</span></a>';
                $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="cancel" data-order_id="' . $row->id . '" href="#"><i data-feather="alert-octagon" class="me-50"></i><span>Canecl</span></a>';
                $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="reject" data-order_id="' . $row->id . '" href="#"><i data-feather="crosshair" class="me-50"></i><span>Reject</span></a>';
                break;
            case 4:
                $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="delivered" data-order_id="' . $row->id . '" href="#"><i data-feather="target" class="me-50"></i><span>Delivered</span></a>';
                $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="cancel" data-order_id="' . $row->id . '" href="#"><i data-feather="alert-octagon" class="me-50"></i><span>Canecl</span></a>';
                $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="reject" data-order_id="' . $row->id . '" href="#"><i data-feather="crosshair" class="me-50"></i><span>Reject</span></a>';
                break;
            default:
                # code...
                break;
        }
        return $action;
    }

    private function getCancelActions($row)
    {
        
        $action = '';
        $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="cancel" data-order_id="' . $row->id . '" href="#"><i data-feather="alert-octagon" class="me-50"></i><span>Canecl</span></a>';
        $action .= '<a class="dropdown-item order-action" data-bs-toggle="modal" data-bs-target="#shareProject" data-type="reject" data-order_id="' . $row->id . '" href="#"><i data-feather="crosshair" class="me-50"></i><span>Reject</span></a>';
        return $action;
    }

    public function getInhouseData()
    {
        $seller_order = SellerOrder::where('seller_id', 6)->orderBy('created_at', 'DESC')->get();
        $order_id = collect($seller_order)->map(function ($item) {
            return $item->order_id;
        });
        $order_id = collect($order_id)->unique();
        $data = Order::whereIn('id', $order_id)->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('all_action', function ($row) {
                return '<input type="checkbox"/>';
            })
            ->addColumn('order_by', function ($row) {
                return "<a href='#'>" . $row->user->name ?? null . "[" . $row->user->phone . "]</a>";
            })
            ->addColumn('payment_status', function ($row) {
                return (int)$row->payment_status == 1 ? 'Paid' : 'Not Paid';
            })
            ->addColumn('status', function ($row) {
                $status = '';
                if ((int)$row->pending == 1) {
                    $status = '<div class="d-flex"><span class="badge btn-secondary">Pending</span><span class="badge bg-warning success-status">New</span></div>';
                } else {
                    $status = $this->getStatus($row->status);
                }
                $action = '<div class="d-flex">' . $status . '<div class="dropdown"><button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0" data-bs-toggle="dropdown"><i data-feather="more-vertical"></i></button><div class="dropdown-menu dropdown-menu-end">';

                $action .= $this->getActions($row);

                $action .= '</div></div></div>';
                return $action;
            })
            ->addColumn('ref_id', function ($row) {
                $show =  '<a href="' . route('admin.viewOrder', $row->ref_id ?? 0) . '">' . $row->ref_id . '</a>';
                return  $show;
            })
            ->addColumn('total_discount',function($row)
            {
                return formattedNepaliNumber($row->total_discount);
            })
            ->addColumn('total_price',function($row)
            {
                return formattedNepaliNumber($row->total_price);
            })
            ->addColumn('action', function ($row) {

                // $show='';
                // if($row->status==6)
                // {
                //     $show.='<div class="btn-group"><a href="javascript:;" data-orderId="'.$row->id.'" class="btn btn-sm btn-primary barcode_btn" >View</a>';
                // }

                // $show.=  Utilities::button(href: route('seller-order.show', $row->id ?? 0), icon: "eye", color: "info", title: 'Show Order');
                // $show.="</div>";
                // return  $show;

                $show =  Utilities::button(href: route('admin.viewOrder', $row->ref_id ?? 0), icon: "eye", color: "info success-status", title: 'Show Order');
                return  $show;
            })
            ->rawColumns(['status', 'order_by', 'payment_status', 'all_action', 'action', 'ref_id','total_discount','total_price'])
            ->make(true);
    }

    public function getOrderSeller()
    {

        $seller_order = SellerOrder::whereNot('seller_id', 6)->get();
        $seller_id = collect($seller_order)->map(function ($item) {
            return $item->seller_id;
        });
        $seller_id = collect($seller_id)->unique();

        $data = Seller::whereIn('id', $seller_id)->get();
        // dd($data);
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('all_action', function ($row) {
                return '<input type="checkbox"/>';
            })
            ->addColumn('seller_name', function ($row) {
                return $row->name ?? null;
            })
            ->addColumn('contact_num', function ($row) {
                return $row->phone;
            })
            ->addColumn('total_order', function ($row) {
                $seller_order = SellerOrder::where('seller_id', $row->id)->get();
                $seller_order = collect($seller_order)->map(function ($item) {
                    return $item->order_id;
                });
                $seller_order = collect($seller_order)->unique();

                return count($seller_order) ?? 0;
            })
            ->addColumn('pending_order', function ($row) {
                $seller_order = SellerOrder::where('seller_id', $row->id)->get();
                $seller_order = collect($seller_order)->map(function ($item) {
                    return $item->order_id;
                });
                $seller_order = collect($seller_order)->unique();
                $pending_order = Order::whereIn('id', $seller_order)->whereNot('status', 5)->get();
                return count($pending_order) ?? 0;
            })

            // ->addColumn('payment_status', function ($row) {
            //     $row->phone;
            //     // $show =  '<a href="'.route('admin.viewOrder', $row->ref_id ?? 0).'">'.$row->ref_id.'</a>';
            //     // return  $show;
            // })
            ->addColumn('action', function ($row) {
                $show =  Utilities::button(href: route('admin.sellerOrder', $row->id ?? 0), icon: "eye", color: "info success-status", title: 'Show Order');
                return  $show;
            })
            ->rawColumns(['seller_name', 'contact_num', 'pending_order', 'action', 'total_order'])
            ->make(true);
    }

    public function getsellerOrderView()
    {

        $seller_order = SellerOrder::whereNot('seller_id', 6)->get();
        $seller_id = collect($seller_order)->map(function ($item) {
            return $item->seller_id;
        });
        $seller_id = collect($seller_id)->unique();

        $data = Seller::whereIn('id', $seller_id)->get();
        // dd($data);
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('all_action', function ($row) {
                return '<input type="checkbox"/>';
            })
            ->addColumn('seller_name', function ($row) {
                return $row->name ?? null;
            })
            ->addColumn('contact_num', function ($row) {
                return $row->phone;
            })
            ->addColumn('total_order', function ($row) {
                $seller_order = SellerOrder::where('seller_id', $row->id)->get();
                $seller_order = collect($seller_order)->map(function ($item) {
                    return $item->order_id;
                });
                $seller_order = collect($seller_order)->unique();

                return count($seller_order) ?? 0;
            })
            ->addColumn('pending_order', function ($row) {
                $seller_order = SellerOrder::where('seller_id', $row->id)->get();
                $seller_order = collect($seller_order)->map(function ($item) {
                    return $item->order_id;
                });
                $seller_order = collect($seller_order)->unique();
                $pending_order = Order::whereIn('id', $seller_order)->whereNot('status', 5)->get();
                return count($pending_order) ?? 0;
            })

            // ->addColumn('payment_status', function ($row) {
            //     $row->phone;
            //     // $show =  '<a href="'.route('admin.viewOrder', $row->ref_id ?? 0).'">'.$row->ref_id.'</a>';
            //     // return  $show;
            // })
            ->addColumn('action', function ($row) {
                $show =  Utilities::button(href: route('admin.sellerOrder', $row->id ?? 0), icon: "eye", color: "info success-status", title: 'Show Order');
                return  $show;
            })
            ->rawColumns(['seller_name', 'contact_num', 'pending_order', 'action', 'total_order'])
            ->make(true);
    }

    public function getSellerOrderListView($id = null)
    {

        $seller_order = SellerOrder::where('seller_id', $id)->get();
        $seller_order = collect($seller_order)->map(function ($item) {
            return $item->order_id;
        });
        $seller_order = collect($seller_order)->unique();
        $data = Order::whereIn('id', $seller_order)->get();
        $data = collect($data)->each(function ($item) use ($id) {
            return $item->seller_id = $id;
        });
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('all_action', function ($row) {
                return '<input type="checkbox"/>';
            })
            ->addColumn('order_by', function ($row) {
                return "<a href='#'>" . $row->user->name ?? null . "[" . $row->user->phone . "]</a>";
            })
            ->addColumn('payment_status', function ($row) {
                return (int)$row->payment_status == 1 ? 'Paid' : 'Not Paid';
            })
            ->addColumn('status', function ($row) {
                $status = '';
                if ((int)$row->pending == 1) {
                    $status = '<div class="d-flex"><span class="badge btn-secondary">Pending</span><span class="badge bg-warning success-status">New</span></div>';
                } else {
                    $status = $this->getStatus($row->status);
                }
                $action = '<div class="d-flex">' . $status . '<div class="dropdown"><button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0" data-bs-toggle="dropdown"><i data-feather="more-vertical"></i></button><div class="dropdown-menu dropdown-menu-end">';

                $action .= $this->getActions($row);

                $action .= '</div></div></div>';
                return $action;
            })
            ->addColumn('ref_id', function ($row) {
                $show =  '<a href="' . route('admin.viewOrder', $row->ref_id ?? 0) . '">' . $row->ref_id . '</a>';
                return  $show;
            })
            ->addColumn('qty', function ($row) {
                $seller_order = SellerOrder::where('order_id', $row->id)->where('seller_id', $row->seller_id)->get();
                $qty = null;
                foreach ($seller_order as $order) {
                    $qty += $order->qty;
                }
                return $qty;
            })
            ->addColumn('discount', function ($row) {
                $seller_order = SellerOrder::where('order_id', $row->id)->where('seller_id', $row->seller_id)->get();
                $discount = null;
                foreach ($seller_order as $order) {
                    $discount += $order->total_discount;
                }
                dd(formattedNepaliNumber($discount));
                return formattedNepaliNumber($discount);
            })
            ->addColumn('total', function ($row) {
                $seller_order = SellerOrder::where('order_id', $row->id)->where('seller_id', $row->seller_id)->get();
                $total = null;
                foreach ($seller_order as $order) {
                    $total += $order->total;
                }
                return formattedNepaliNumber($total);
            })
            ->addColumn('payment_status', function ($row) {
                return $row->payment_status == 0 ? 'Not Paid' : 'Paid';
            })
            ->addColumn('action', function ($row) {

                $show =  Utilities::button(href: route('admin.viewOrder', $row->ref_id ?? 0), icon: "eye", color: "info success-status", title: 'Show Order');
                return  $show;
            })
            ->rawColumns(['status', 'order_by', 'payment_status', 'all_action', 'action', 'ref_id', 'discount', 'total'])
            ->make(true);
    }

    public function getAllDelivery()
    {
        $data = Order::where('status', 5)->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('all_action', function ($row) {
                return '<input type="checkbox"/>';
            })
            ->addColumn('order_by', function ($row) {
                return "<a href='#'>" . $row->user->name ?? null. "[" . $row->user->phone . "]</a>";
            })
            ->addColumn('payment_status', function ($row) {
                return (int)$row->payment_status == 1 ? 'Paid' : 'Not Paid';
            })
            ->addColumn('ref_id', function ($row) {
                $show =  '<a href="' . route('admin.viewOrder', $row->ref_id ?? 0) . '">' . $row->ref_id . '</a>';
                return  $show;
            })
            ->addColumn('qty', function ($row) {

                return $row->total_quantity;
            })
            ->addColumn('received_on', function ($row) {

                return $row->created_at;
            })
            ->addColumn('discount', function ($row) {

                return formattedNepaliNumber($row->total_discount);
            })
            ->addColumn('total', function ($row) {

                return formattedNepaliNumber($row->total_price);
            })
            ->addColumn('payment_status', function ($row) {
                return $row->payment_status == 0 ? 'Not Paid' : 'Paid';
            })
            ->addColumn('action', function ($row) {
                $show =  Utilities::button(href: route('admin.viewOrder', $row->ref_id ?? 0), icon: "eye", color: "info success-status", title: 'Show Order');
                return  $show;
            })
            ->rawColumns(['order_by', 'payment_status', 'all_action', 'action', 'ref_id', 'discount', 'total','received_on'])
            ->make(true);
    }
}
