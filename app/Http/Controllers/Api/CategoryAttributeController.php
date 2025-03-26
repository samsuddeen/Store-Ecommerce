<?php

namespace App\Http\Controllers\Api;

use App\Models\City;
use App\Models\Color;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductCity;
use Illuminate\Http\Request;
use App\Models\ProductAttribute;
use App\Models\CategoryAttribute;
use App\Helpers\ProductFormHelper;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Jobs\CategoryAttributeUpdateJob;
use App\Actions\Product\ProductFormAction;
use App\Observers\Category\CategoryObserver;

class CategoryAttributeController extends Controller
{
    public function attribute(Request $request)
    {
        $category = Category::with('attributes')->find($request->category_id);
        $attributes = $category->attributes()->select('id', 'title','value','helpText','stock')->get();

        return response()->json($attributes, 200);
    }

    public function attributeNew(Request $request)
    {
        $product=Product::where('id',$request->productId)->first();
        if($request->catId==$product->category_id)
        {
            $data = (new ProductFormAction($product))->getData();
            $data['featured_images'] = ProductFormHelper::getFeaturedImage($product);
            $data['dangerous_goods'] = ProductFormHelper::getDangerousGoods($product);
          
            $category = Category::find($product->category_id);
            $category_element = "";
            if($category){
                $root_element = $category->ancestors;
                if(count($root_element) > 0){
                    foreach($root_element as $element){
                        $category_element .= $element->title .' > ';
                    }
                }
                $category_element .= $category->title;
            }
            $data['category_element'] = $category_element;
            $data['categories'] = Category::where('status',1)->where('parent_id', '=', null)->orderBy('created_at')->get();
            $data['cities'] = City::get();
            $data['current_cities'] = ProductCity::where('product_id',$product->id)->get();
            $data['p_attributes'] = ProductAttribute::where('product_id',$product->id)->get();
            return view('catupdatenew1',$data);
            
        }
        $category = Category::with('attributes')->find($request->catId);
        $data['attributes'] = $category->attributes()->select('id', 'title','value','helpText','stock')->get();
        $data['colors']=Color::where('status',1)->get();
        return view('catupdatenew',$data);
        // return response()->json($data, 200);
    }

    public function getLatestAttributeData(Request $request){
        $category=Category::where('id',$request->catIdValue)->first();
        $attributes = $category->attributes;
        $response=[
            'error'=>false,
            'data'=>$attributes,
            'msg'=>'Category Attribute'
        ];
        return response()->json($response,200);
    }


    public function deleteAttribute(CategoryAttribute $categoryAttribute)
    {
        DB::beginTransaction();
        try {
            // dispatch(new CategoryAttributeUpdateJob($categoryAttribute));
            (new CategoryObserver($categoryAttribute->category, $categoryAttribute))->observe();
            DB::commit();
            return response()->json('Successfully Deleted', 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json($th->getMessage(), 500);
        }
    }
}
