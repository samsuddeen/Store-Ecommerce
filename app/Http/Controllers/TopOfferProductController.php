<?php

namespace App\Http\Controllers;

use App\Data\Offer\Product\TopOfferProductData;
use App\Models\Admin\Offer\Product\TopOfferProduct;
use App\Http\Controllers\Controller;
use App\Models\Admin\Offer\TopOffer;
use App\Models\Customer;
use App\Models\New_Customer;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class TopOfferProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(TopOffer $topOffer)
    {
        $data = $this->getData($topOffer);
        return view("admin.featured-section.product.index", $data);
        

        $topOfferProduct = TopOfferProduct::paginate(20);
        return view("admin.top-offer.product.index",compact("topOfferProduct"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($offer_id)
    {
     
        $offer = TopOffer::find($offer_id);
    
      
        $customers = New_Customer::all();
    
       
        $topOfferProduct = new TopOfferProduct();
        $data = (new TopOfferProductData())->getData();
        $data['topOfferProduct'] = $topOfferProduct;
        $data['offer'] = $offer;
        $data['customers'] = $customers;  
    
        // Return the view with the data
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
            "top_offer_id" => "required",
            "product_id" => "required"
        ]);
        DB::beginTransaction();
        try{
            if($request->product_id == 'All')
            {
                $products = Product::where('status',1)->get();
                foreach ($products as $product) {
                    TopOfferProduct::create([
                        'top_offer_id' => $request->top_offer_id,
                        'product_id' => $product->id,
                    ]);
                }
            }else{
                TopOfferProduct::create($request->all());
            }
            session()->flash('success',"new TopOfferProduct created successfully");
            DB::commit();
            return redirect()->route('top-offer.index');
        } catch (\Throwable $th) {
            session()->flash('error',$th->getMessage());
            DB::rollback();
            return redirect()->back()->withInput();

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Admin\Offer\Product\TopOfferProduct  $topOfferProduct
     * @return \Illuminate\Http\Response
     */
    public function show(TopOfferProduct $topOfferProduct)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Admin\Offer\Product\TopOfferProduct  $topOfferProduct
     * @return \Illuminate\Http\Response
     */
    public function edit(TopOfferProduct $topOfferProduct)
    {
        return view("admin.TopOfferProduct.form",compact("topOfferProduct"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Admin\Offer\Product\TopOfferProduct  $topOfferProduct
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TopOfferProduct $topOfferProduct)
    {
         DB::beginTransaction();
          try{
           $topOfferProduct->update($request->all());
            session()->flash('success',"new TopOfferProduct created successfully");
            DB::commit();
            return redirect()->route('top-offer.index');

        } catch (\Throwable $th) {
            session()->flash('error',$th->getMessage());
            DB::rollback();
            return redirect()->back()->withInput();

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Admin\Offer\Product\TopOfferProduct  $topOfferProduct
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {   
        try{
            $topOfferProduct = TopOfferProduct::find($id);
            if($topOfferProduct){
                $topOfferProduct->delete();
                session()->flash('success',"TopOfferProduct deleted successfully");
                 return back();
            }

        } catch (\Throwable $th) {
               session()->flash('error',$th->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function getData(TopOffer $topOffer)
    {
        $data = [
            'top_offers'=>$topOffer->offerProducts,
        ];
        return $data;
    }
}
