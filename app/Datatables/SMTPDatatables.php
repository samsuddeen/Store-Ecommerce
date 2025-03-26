<?php

namespace App\Datatables;

use App\Models\SMTP\SMTP;
use App\Helpers\Utilities;
use Yajra\DataTables\Facades\DataTables;

class SMTPDatatables implements DatatablesInterface
{

    public function getData()
    {
        $data = SMTP::latest();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('email', function ($row) {
                return '<a href="#">'.$row->mail_user_name.'</a>';
            })
            ->addColumn('action', function ($row) {
                $edit =  Utilities::button(href: route('smtp.edit', $row->id), icon: "edit", color: "primary", title: 'Edit ?Banner');
                $delete = Utilities::delete(href: route('smtp.destroy', $row->id), id: $row->id);
                return  $edit . '' . $delete;
            })
            ->rawColumns(['action', 'email'])
            ->make(true);
    }
}
