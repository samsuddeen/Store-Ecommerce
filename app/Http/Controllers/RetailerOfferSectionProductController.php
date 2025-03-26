<?php

namespace App\Http\Controllers;

use App\Models\New_Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\RetailerOfferSection;
use App\Models\RetailerOfferProductList;
use App\Models\RetailerOfferRetailerList;
use App\Data\Offer\Product\RetailerTopOfferProductData;

class RetailerOfferSectionProductController extends Controller
{
    public function index(RetailerOfferSection $RetailerOfferSection)
    {
        $data = $this->getData($RetailerOfferSection);
        return view("admin.featured-section.product.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($offer_id)
    {
        $data =(new RetailerTopOfferProductData())->getData();
        $data['retailerOfferSection']=RetailerOfferSection::findOrFail($offer_id);
        $data['retailers']=New_Customer::where('wholeseller','1')->get();
        $data['retailerList']=$data['retailerOfferSection']->retailerList;
        $data['productList']=$data['retailerOfferSection']->productList;
        return view("admin.top-offer.product.form", $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        $this->validate($request, [
            "retailer_offer_section" => "required|exists:retailer_offer_sections,id",
            "retailer_id*" => "required|exists:tbl_customers,id",
            "product_id*" => "required|exists:products,'id"
        ]);
        $retailerOfferSection=RetailerOfferSection::findOrFail($request->retailer_offer_section);
        DB::beginTransaction();
        try{
            $retailerCollection=[];
            $productCollection=[];
            foreach($request->retailer_id as $retailerid){
                $retailerCollection[]=[
                    'retailer_offer_id'=>$retailerOfferSection->id,
                    'retailer_id'=>$retailerid
                ];
            }
            if($retailerOfferSection->retailerList && count($retailerOfferSection->retailerList) >0){
                $retailerOfferSection->retailerList()->delete();
            }
            RetailerOfferRetailerList::insert($retailerCollection);
            foreach($request->product_id as $productId){
                $productCollection[]=[
                    'retailer_offer_id'=>$retailerOfferSection->id,
                    'product_id'=>$productId
                ];
            }
            if($retailerOfferSection->productList && count($retailerOfferSection->productList) >0){
                $retailerOfferSection->productList()->delete();
            }
            RetailerOfferProductList::insert($productCollection);
            session()->flash('success',"Added Successfully !!");
            DB::commit();
            return redirect()->route('retailer_offer.index');
        } catch (\Throwable $th) {
            session()->flash('error',$th->getMessage());
            DB::rollback();
            return redirect()->back()->withInput();

        }
    }

    
}
