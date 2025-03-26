<?php

namespace App\Http\Controllers;

use DataTables;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\SellerCommission;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\SellerCommissionCategory;
use App\Http\Requests\SellerCommissionStoreRequest;
use App\Models\SellerCommissionBrand;

class SellerCommissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $folder_name = "admin.sellercommission.";
    protected $seller_commission=null;
    protected $seller_commission_category=null;
    protected $seller_commission_brand=null;
    public function __construct(SellerCommission $seller_commission,SellerCommissionCategory $seller_commission_category,SellerCommissionBrand $seller_commission_brand)
    {
        $this->seller_commission=$seller_commission;
        $this->seller_commission_category=$seller_commission_category;
        $this->seller_commission_brand=$seller_commission_brand;
    }
    public function index(Request $request)
    {
        $commisssion = SellerCommission::all();
        if ($request->ajax()) {
            $commisssion = SellerCommission::all();
            return Datatables::of($commisssion)
                ->addIndexColumn()
                ->addColumn('id', function ($row) {
                    return $row->id;
                })
                ->addColumn('title', function ($row) {
                    return $row->title;
                })
                ->addColumn('category', function ($row) {
                    $cat=$row->category->pluck('category_id')->toArray();
                    $category=Category::get();
                    $category=collect($category)->filter(function($index) use ($cat)
                    {
                        if(in_array($index->id,$cat))
                        {
                            return $index;
                    }
                    });
                    
                    $dataHtml='';
                    foreach($category as $data)
                    {
                        $dataHtml.='<a class="btn btn-sm btn-primary process-status" href=#>'.$data->title.'</a>';
                    }
                    return $dataHtml;
                })
                ->addColumn('brand', function ($row) {
                    
                    $cat=$row->brand->pluck('brand_id')->toArray();
                    $category=Brand::get();
                    $category=collect($category)->filter(function($index) use ($cat)
                    {
                        if(in_array($index->id,$cat))
                        {
                            return $index;
                    }
                    });
                    
                    $dataHtml='';
                    foreach($category as $data)
                    {
                        $dataHtml.='<a class="btn btn-sm btn-primary process-status" href=#>'.$data->name.'</a>';
                    }
                    return $dataHtml;
                })
                ->addColumn('action', function ($row) {
                    $route1 = route('sellercommission.edit', $row->id);
                    $html = "";
                    $html = "<a class='btn btn-sm btn-primary process-status' href='$route1'> <i data-feather='edit'></i> </a>";
                    return $html;
                })
                ->rawColumns(['id', 'title', 'category', 'brand','action'])
                ->make(true);
        }
        return view($this->folder_name . "index", compact('commisssion'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $category=Category::get();
        $brand=Brand::get();
        $seller_commission=null;
        return view($this->folder_name . "form",compact(['category','brand','seller_commission']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SellerCommissionStoreRequest $request)
    {
        DB::beginTransaction();
        $data = $request->all();
        
        try{
            $this->seller_commission->fill($data);
            $this->seller_commission->save();
            $category=[];
            $brand=[];
            if($request->category_id)
            {
                foreach($request->category_id as $cat_id)
                {
                    $category[]=[
                        'seller_commisssion_id'=>$this->seller_commission->id,
                        'category_id'=>$cat_id
                    ];
                }
            }

            if($request->brand_id)
            {
                foreach($request->brand_id as $cat_id)
                {
                    $brand[]=[
                        'seller_commisssion_id'=>$this->seller_commission->id,
                        'brand_id'=>$cat_id
                    ];
                }
            }
            
            $this->seller_commission_category->insert($category);
            $this->seller_commission_brand->insert($brand);
            session()->flash('success',"Seller Commission Creeated Successfully");
            DB::commit();
            return redirect()->route('sellercommission.index');
        } catch (\Throwable $th) {
            session()->flash('error',$th->getMessage());
            DB::rollback();
            return redirect()->back()->withInput();

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($seller_commission)
    {
        $this->seller_commission=SellerCommission::findOrFail($seller_commission);
        
        $category=Category::get();
        $brand=Brand::get();
        return view($this->folder_name . "form",compact(['category','brand']))
        ->with('seller_commission',$this->seller_commission);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->seller_commission=SellerCommission::findOrFail($id);
        DB::beginTransaction();
        $data = $request->all();
        try{
            $this->seller_commission->fill($data);
            $this->seller_commission->save();
            $category=[];
            $brand=[];
            if($request->category_id)
            {
                $this->seller_commission->category()->delete();
                foreach($request->category_id as $cat_id)
                {
                    $category[]=[
                        'seller_commisssion_id'=>$this->seller_commission->id,
                        'category_id'=>$cat_id
                    ];
                }
            }

            if($request->brand_id)
            {
                $this->seller_commission->brand()->delete();
                foreach($request->brand_id as $cat_id)
                {
                    $brand[]=[
                        'seller_commisssion_id'=>$this->seller_commission->id,
                        'brand_id'=>$cat_id
                    ];
                }
            }
            
            $this->seller_commission_category->insert($category);
            $this->seller_commission_brand->insert($brand);
            session()->flash('success',"Seller Commission Updated Successfully");
            DB::commit();
            return redirect()->route('sellercommission.index');
        } catch (\Throwable $th) {
            session()->flash('error',$th->getMessage());
            DB::rollback();
            return redirect()->back()->withInput();

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->seller_commission=SellerCommission::findOrFail($id);
        DB::beginTransaction();
        try{
            $this->seller_commission->delete();
            
            session()->flash('success',"Seller Commission Deleted Successfully");
            DB::commit();
            return redirect()->route('sellercommission.index');
        } catch (\Throwable $th) {
            session()->flash('error',$th->getMessage());
            DB::rollback();
            return redirect()->back()->withInput();

        }
    }
}
