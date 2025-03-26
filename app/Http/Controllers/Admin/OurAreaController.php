<?php

namespace App\Http\Controllers\Admin;

use DataTables;
use App\Models\Local;
use App\Models\District;
use App\Models\Province;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\City;

class OurAreaController extends Controller
{
    protected $folder_name = "admin.area.";
    public function Province(Request $request)
    {
        $provinces = Province::all();
        if ($request->ajax()) {
            $provinces = Province::all();
            return Datatables::of($provinces)
                ->addIndexColumn()
                ->addColumn('id', function ($row) {
                    return $row->id;
                })
                ->addColumn('area', function ($row) {
                    return $row->eng_name ?? null;
                })
                ->addColumn('publish', function ($row) {
                    $route = route('province.change.status', $row->id);
                    $html = "";
                    $html = "<a href='$route'>" . (($row->publishStatus == 1) ? 'Active' : 'In-Active') . "</a>";
                    return $html;
                })
                ->addColumn('action', function ($row) {
                    $route = route('province.edit', $row->id);
                    $html = "";
                    $html = "<a href='$route'> Edit </a>";
                    return $html;
                })
                ->rawColumns(['id', 'area', 'publish', 'action'])
                ->make(true);
        }
        return view($this->folder_name . "province", compact('provinces'));
    }




    public function district(Request $request)
    {
        $districts = District::all();
        if ($request->ajax()) {
            $districts = District::all();
            return Datatables::of($districts)
                ->addIndexColumn()
                ->addColumn('id', function ($row) {
                    return $row->id;
                })
                ->addColumn('province', function ($row) {
                    $province = Province::where('id', $row->province)->first();
                    return $province->eng_name ?? null;
                })
                ->addColumn('district', function ($row) {
                    return $row->np_name ?? null;
                })
                ->addColumn('publish', function ($row) {
                    // $route = route('district.change.status', $row->id);
                    $html = "";
                    $html = "<a href='#' class='change-status' data-id='" . $row->id . "'>" . (($row->publishStatus == 1) ? 'Active' : 'In-Active') . "</a>";
                    return $html;
                })
                ->addColumn('action', function ($row) {
                    $route = route('district.edit', $row->id);
                    $html = "";
                    $html = "<a href='$route'> Edit </a>";
                    return $html;
                })
                ->rawColumns(['id', 'province', 'district', 'publish', 'action'])
                ->make(true);
        }
        return view($this->folder_name . "districts", compact('districts'));
    }

    public function local(Request $request)
    {
        $locals = City::with('district')->get();

        if ($request->ajax()) {
            $locals = City::with('district')->get();
            return Datatables::of($locals)
                ->addIndexColumn()
                ->addColumn('id', function ($row) {
                    return $row->id;
                })
                ->addColumn('province', function ($row) {
                    $province = Province::select('eng_name')->where('id',$row->district->province)->first();
                    return $province->eng_name ?? null;
                })
                ->addColumn('district', function ($row) {

                    return $row->district->np_name;
                })
                ->addColumn('area', function ($row) {
                    return $row->city_name;
                })
                // ->addColumn('publish', function ($row) {
                //     $html = "";
                //     $html = "<a href='#' class='change-publishStatus' data-id='" . $row->id . "'>" . (($row->publishStatus == 1) ? 'Active' : 'In-Active') . "</a>";
                //     return $html;
                // })
                ->addColumn('action', function ($row) {
                    $route = route('local.edit', $row->id);
                    $html = "";
                    $html = "<a href='$route'> Edit </a>";
                    return $html;
                })
                // ->addColumn('action', function ($row) {
                //     $btn = "";
                //     $btn .= '<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal' . $row->id . '"> Change Name </button>';
                //     return $btn;
                // })
                ->rawColumns(['id', 'province', 'district', 'area', 'action'])
                ->make(true);
        }
        return view($this->folder_name . "locals", compact('locals'));
    }
}
