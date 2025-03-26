<?php

namespace App\Datatables;

use App\Helpers\Utilities;
use App\Models\Seller;
use App\Models\Trash\Trash;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Database\Eloquent\Model;

class TrashDatatables implements DatatablesInterface
{

    public function getData()
    {

        $data = Trash::latest();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('dataName', function ($data) {
                $model = $data->model;
                $newModel = '\\' . $model;
                $dataItem = $newModel::withTrashed()->where('id', $data->model_id)->first() ?? null;
                // dd($dataItem);
               
                $propertiesArray = $dataItem ? $dataItem->toArray() : [];
                $indexedArray = array_values($propertiesArray); // Reindex the array numerically
                if (isset($indexedArray[1])) {
                    $title = $indexedArray[1];
                } else {
                    $title = '';
                }

                return $title;
            })
            ->addColumn('deleted_at', function ($row) {
                return $row->created_at->format('M, d Y') . ', [' . $row->created_at->format('H:i') . ']';
            })
            ->addColumn('action', function ($row) {
                return $this->getAction($row);
            })
            ->rawColumns(['action', 'document', 'dataName'])
            ->make(true);
    }

    private function getAction($row)
    {

        $action  = '';
        $action .= '<a class="btn btn-success restore" href="#" data-name="' . $row->name . '" data-action_type="PATCH" data-id="' . $row->id . '" data-action="' . route('trash.restore', $row->id) . '">Restore</a>';
        $action .= '<a class="btn btn-danger restore" href="#" data-name="' . $row->name . '" data-action_type="DELETE" data-id="' . $row->id . '" data-action="' . route('trash.destroy', $row->id) . '">Delete</a>';
        return $action;
    }
}
