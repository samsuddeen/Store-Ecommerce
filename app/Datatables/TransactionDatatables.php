<?php

namespace App\Datatables;

use App\Helpers\Utilities;
use App\Models\Order;
use App\Models\Transaction\Transaction;
use Yajra\DataTables\Facades\DataTables;

class TransactionDatatables implements DatatablesInterface
{

    public function getData()
    {
        $data = Transaction::latest();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('order_by', function ($row) {
                return "<a href='#'>" . $row->order->user->name . "[" . $row->order->user->phone . "]</a>";
            })
            ->addColumn('transaction_no', function ($row) {
                return  $row->transaction_no;
            })
            ->addColumn('total_quantity', function ($row) {
                return  $row->order->total_quantity ?? "Not Defined";
            })
            ->addColumn('total_discount', function ($row) {
                return  '$.' .$row->order->total_discount ?? "Not Defined";
            })
            ->addColumn('total_price', function ($row) {
                return  '$.'.$row->order->total_price ?? "Not Defined";
            })
            ->addColumn('delivery_date', function ($row) {
                return  ($row->created_at->format('d M Y').'['.$row->created_at->format('H:i').']') ?? "Not Defined";
            })
            ->addColumn('ref_id', function ($row) {
                $show =  '<a href="'.route('admin.viewOrder', $row->order->ref_id).'">'.$row->order->ref_id.'</a>';
                return  $show;
            })
            ->rawColumns(['status', 'order_by', 'ref_id', 'transaction_no', 'total_quantity', 'total_discount', 'total_price', 'delivery_date'])
            ->make(true);
    }

}
