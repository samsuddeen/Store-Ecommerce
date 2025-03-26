<?php

namespace App\Http\Controllers;

use DataTables;
use App\Models\Admin\VatTax;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class VatTaxController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $vatTax = VatTax::all();
        // dd($vatTax);
        if ($request->ajax()) {
            $vatTax = VatTax::all();
            return Datatables::of($vatTax)
                ->addIndexColumn()
                ->addColumn('id', function ($row) {
                    return $row->id;
                })
                ->addColumn('vat_per', function ($row) {
                    return $row->vat_percent ?? null;
                })
                ->addColumn('action', function ($row) {
                    $route = route('vat-tax.edit', $row);
                    $btn = "";
                    $btn .= '<a href="' . $route . '" class="btn btn-primary"  >Edit</a>';
                    return $btn;
                })
                ->rawColumns(['id', 'vat_per','action'])
                ->make(true);
        }
        // return view('admin.seller.review.index')->with('reviews',$reviews);
        return view("admin.vattax.index", compact("vatTax"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $vatTax = new VatTax();
        return view("admin.vattax.form", compact("vatTax"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'vat_percent' => 'required',
            'tax_percent' => 'required',
            'publishStatus' => 'required',
        ]);

        $input = $request->all();
        $input['created_by'] = auth()->user()->id;
        $v = Carbon::now('Y');
        $input['created_year'] = date('Y', strtotime($v));
        $vatTax =  VatTax::where('created_year', $input['created_year'])->first();

        if ($vatTax != null) {
            session()->flash('erorr', 'The Vat & Tax Already created For the year. You can edit now.');
            return redirect()->route('vat-tax.edit', $vatTax);
        }

        DB::beginTransaction();
        try {
            VatTax::create($input);
            session()->flash('success', "VatTax created successfully");
            DB::commit();
            return redirect()->route('vat-tax.index');
        } catch (\Throwable $th) {
            session()->flash('error', $th->getMessage());
            DB::rollback();
            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Admin\VatTax  $vatTax
     * @return \Illuminate\Http\Response
     */
    public function show(VatTax $vatTax)
    {
        //
        dd($vatTax);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Admin\VatTax  $vatTax
     * @return \Illuminate\Http\Response
     */
    public function edit(VatTax $vatTax)
    {
        return view("admin.vattax.form", compact("vatTax"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Admin\VatTax  $vatTax
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, VatTax $vatTax)
    {

        $request->validate([
            'vat_percent' => 'required',
            'tax_percent' => 'required',
        ]);

        $input = $request->all();
        $input['publishStatus']=1;

        DB::beginTransaction();
        try {
            $vatTax->update($input);
            session()->flash('success', "VatTax updated successfully");
            DB::commit();
            return redirect()->route('vat-tax.index');
        } catch (\Throwable $th) {
            session()->flash('error', $th->getMessage());
            DB::rollback();
            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Admin\VatTax  $vatTax
     * @return \Illuminate\Http\Response
     */
    public function vatTaxDelete($id)
    {
        $vatTax = VatTax::where('id', $id)->first();
        try {
            $vatTax->delete();
            session()->flash('success', "VatTax deleted successfully");
            return redirect()->route('vat-tax.index');
        } catch (\Throwable $th) {
            session()->flash('error', $th->getMessage());
            return redirect()->back()->withInput();
        }
    }
}
