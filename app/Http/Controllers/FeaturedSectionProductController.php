<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Admin\Offer\TopOffer;
use Illuminate\Support\Facades\Validator;
use App\Enum\Featured\FeaturedSectionEnum;
use App\Actions\Featured\FeaturedProductFormAction;
use App\Models\Admin\Product\Featured\FeaturedSection;
use App\Traits\Product\Featured\FeaturedProductSection;
use App\Models\Admin\Product\Featured\FeaturedSectionProduct;

class FeaturedSectionProductController extends Controller
{
    use FeaturedProductSection;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(FeaturedSection $featuredSection)
    {
        $data = $this->getData($featuredSection);
        return view("admin.featured-section.product.index", $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $type,$id)
    {
        $featuredData=FeaturedSection::findOrFail($id);
        $foreignData = [];
        switch ($type) {
            case 'CATEGORY':
                $foreignData = Category::orderBy('title', 'asc')->get();
                break;
            case 'BRAND':
                $foreignData = Brand::orderBy('name', 'asc')->get();
                break;
            case 'PRODUCT':
                $foreignData = Product::orderBy('name', 'asc')->get();
                break;
            default:
                $foreignData = Category::oredeBy('title', 'asc')->get();
                break;
        }
        $type_val = (new FeaturedSectionEnum)->getSingleValueByIndex($type);
        $featuredSections =(new FeaturedProductFormAction())->getAllFeaturedSection();
        $featuredSection = new FeaturedSectionProduct();
        $featuredSection->type = $type_val;
        $data =[
            'type'=>$type_val,
            'featured_sections'=>$featuredSections,
            'foreign_data'=>$foreignData,
            'featuredSection'=>$featuredSection,
            'featuredData'=>$featuredData
        ];
        // dd($data);
        return view("admin.featured-section.product.form", $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $validator = $this->validation($request);
        if($validator->fails()){
            return back()->withInput()->withErrors($validator->errors());
        }
        $featuredSection=FeaturedSection::findOrFail($request->featured_section_id);
        
        DB::beginTransaction();
        try{

            $featuredSection->featured()->delete();
            $temp=[];
             foreach($request->foreign_id as $foreign_id){
                $temp[]=[
                    'featured_section_id'=>$request->featured_section_id,
                    'foreign_id'=>$foreign_id
                ];
            }
            FeaturedSectionProduct::insert($temp);
            session()->flash('success',"new Featured Section Product created successfully");
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
     * @param  \App\Models\Admin\Product\Featured\FeaturedSectionProduct  $featuredSectionProduct
     * @return \Illuminate\Http\Response
     */
    public function show(FeaturedSectionProduct $featuredSectionProduct)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Admin\Product\Featured\FeaturedSectionProduct  $featuredSectionProduct
     * @return \Illuminate\Http\Response
     */
    public function edit(FeaturedSectionProduct $featuredSectionProduct)
    {
        return view("admin.featured-section.product.form",compact("featuredSectionProduct"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Admin\Product\Featured\FeaturedSectionProduct  $featuredSectionProduct
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FeaturedSectionProduct $featuredSectionProduct)
    {
        // dd($request->all());
         DB::beginTransaction();
          try{
           $featuredSectionProduct->update($request->all());
            session()->flash('success',"new FeaturedSectionProduct created successfully");
            DB::commit();
            return redirect()->route('FeaturedSectionProduct.index');

        } catch (\Throwable $th) {
            session()->flash('error',$th->getMessage());
            DB::rollback();
            return redirect()->back()->withInput();

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Admin\Product\Featured\FeaturedSectionProduct  $featuredSectionProduct
     * @return \Illuminate\Http\Response
     */
    public function destroy(FeaturedSectionProduct $featuredSectionProduct)
    {
        try{
             $featuredSectionProduct->delete();
              session()->flash('success',"FeaturedSectionProduct deleted successfully");
            return redirect()->route('FeaturedSectionProduct.index');
        } catch (\Throwable $th) {
               session()->flash('error',$th->getMessage());
            return redirect()->back()->withInput();
        }
    }
    public function validation($request)
    {
        $validator = Validator::make($request->all(), [
            'foreign_id'=>'required|array',
            'featured_section_id'=>'required',
        ]);
        return $validator;
    }
}
