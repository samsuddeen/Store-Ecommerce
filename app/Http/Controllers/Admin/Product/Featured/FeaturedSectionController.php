<?php

namespace App\Http\Controllers\Admin\Product\Featured;

use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Actions\Trash\TrashAction;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Enum\Featured\FeaturedSectionEnum;
use App\Http\Requests\LatestProductSetRequest;
use App\Http\Requests\FeatureSectionStoreRequest;
use App\Http\Requests\FeatureSectionUpdateRequest;
use App\Models\Admin\Product\Featured\FeaturedSection;
use App\Models\SelectedProduct;

class FeaturedSectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("admin.featured-section.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $featuredSection = new FeaturedSection();
        $types = (new FeaturedSectionEnum)->getAllValues();
        $data =[
            'featuredSection'=>$featuredSection,
            'types'=>$types,
        ];
        return view("admin.featured-section.form", $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FeatureSectionStoreRequest $request)
    {
        // dd($request->all());
        DB::beginTransaction();
        try{
            $input = $request->all();
            $input['slug'] = Str::slug($request->title);
            FeaturedSection::create($input);
            session()->flash('success',"new Featured Section created successfully");
            DB::commit();
            return redirect()->route('featured-section.index');
        } catch (\Throwable $th) {
            session()->flash('error',$th->getMessage());
            DB::rollback();
            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Admin\Product\Featured\FeaturedSection  $featuredSection
     * @return \Illuminate\Http\Response
     */
    public function show(FeaturedSection $featuredSection)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Admin\Product\Featured\FeaturedSection  $featuredSection
     * @return \Illuminate\Http\Response
     */
    public function edit(FeaturedSection $featuredSection)
    {
        $types = (new FeaturedSectionEnum)->getAllValues();
        $data = [
            'featuredSection'=>$featuredSection,
            'types'=>$types,
        ];
        return view("admin.featured-section.form", $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Admin\Product\Featured\FeaturedSection  $featuredSection
     * @return \Illuminate\Http\Response
     */
    public function update(FeatureSectionUpdateRequest $request, FeaturedSection $featuredSection)
    {
         DB::beginTransaction();
          try{
            $input = $request->all();
            $input['slug'] = Str::slug($request->title);
            $featuredSection->update($input);
            session()->flash('success',"new FeaturedSection created successfully");
            DB::commit();
            return redirect()->route('featured-section.index');
        } catch (\Throwable $th) {
            session()->flash('error',$th->getMessage());
            DB::rollback();
            return redirect()->back()->withInput();

        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Admin\Product\Featured\FeaturedSection  $featuredSection
     * @return \Illuminate\Http\Response
     */
    public function destroy(FeaturedSection $featuredSection)
    {
        try{
            (new TrashAction($featuredSection, $featuredSection->id))->makeRecycle();
             $featuredSection->delete();
              session()->flash('success',"Featured Section deleted successfully");
            return redirect()->route('featured-section.index');
        } catch (\Throwable $th) {
               session()->flash('error',$th->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function setSelectedProduct(Request $request){
        $data['products']=Product::get();
        $data['selectedProducts']=SelectedProduct::pluck('product_id')->toArray();
        return view("admin.top-offer.product.formselectedproduct", $data);
    }

    public function storeSelectedProduct(LatestProductSetRequest $request){
        try{
                $allData=SelectedProduct::get();
                if($allData && count($allData) > 0){
                    foreach($allData as $data){
                        $data->delete();
                    }
                }
                $temp=[];
                foreach($request->product_id as $productId){
                    $temp[]=[
                        'product_id'=>$productId
                    ];
                }
                SelectedProduct::insert($temp);
              session()->flash('success',"Product Added successfully");
            return redirect()->back();
        } catch (\Throwable $th) {
               session()->flash('error',$th->getMessage());
            return redirect()->back()->withInput();
        }
    }
}
