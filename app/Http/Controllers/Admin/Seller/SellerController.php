<?php

namespace App\Http\Controllers\Admin\Seller;

use App\Actions\Seller\SellerAction;
use App\Data\Filter\FilterData;
use App\Data\Location\ProvinceData;
use App\Data\Seller\SellerData;
use App\Http\Controllers\Controller;
use App\Http\Requests\SellerRequest;
use App\Http\Requests\SellerUpdateRequest;
use App\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SellerController extends Controller
{
    function __construct()
    {
        $this->middleware(['permission:sellers-read'], ['only' => ['index']]);
        $this->middleware(['permission:sellers-create'], ['only' => ['create', 'store']]);
        $this->middleware(['permission:sellers-edit'], ['only' => ['edit', 'update']]);
        $this->middleware(['permission:sellers-delete'], ['only' => ['destroy']]);
    }
    public function index(Request $request)
    {
        $filters = array_merge([], $request->all());
        $data['sellers'] = (new SellerData($filters))->getData();
        return view('admin.seller.index', $data);
    }
    public function create(Request $request)
    {
        $filters = array_merge([], $request->all());
        $data = [];
        $data['seller'] = new Seller();
        $data['provinces'] = (new ProvinceData($filters))->getData();
        return view('admin.seller.form', $data);
    }
    public function store(SellerRequest $request)
    {

        DB::beginTransaction();
        try {
            (new SellerAction($request))->store();
            DB::commit();
            return redirect()->route('seller.index')->with('success', 'Successfully Selle has been created');
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', $th->getMessage());
        }
    }

    public function edit(Request $request, Seller $seller)
    {
        $filters = (new FilterData($request))->getData();
        $data['seller'] = $seller;
        $data['provinces'] = (new ProvinceData($filters))->getData();
        return view('admin.seller.form', $data);
    }

    public function update(SellerUpdateRequest $request, Seller $seller)
    {
        DB::beginTransaction();
        try {
            (new SellerAction($request))->update($seller);
            DB::commit();
            return redirect()->route('seller.index')->with('success', 'Seller Successfully Updated');
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->withInput()->with('error', $th->getMessage());
        }
    }

    public function show(Seller $seller)
    {
       
        $user= $seller;
      
        return view('admin.seller.show',compact('user'));
    }

    public function destroy(Seller $seller)
    {
        $seller->delete();
        return redirect()->route('seller.index')->with('success', 'Succesfully Deleted');
    }

    public function updateStatus(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'seller_id'=>'required',
            'status'=>'required',
        ]);
        if($validator->fails()){
            session()->flash('error', 'Sorry Something is wrong');
            return back()->withInput();
        }
        $seller = Seller::findOrFail($request->seller_id);
        DB::beginTransaction();
        try {
           $seller->update([
                'status'=>$request->status,
           ]);
           DB::commit();
           session()->flash('success', 'Successfully Updated');
           return redirect()->route('seller.index');
        } catch (\Throwable $th) {
            DB::rollBack();
            session()->flash('error', 'Sorry Something is wrong');
            return back()->withInput();
        }
    }
}
