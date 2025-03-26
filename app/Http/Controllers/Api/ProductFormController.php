<?php

namespace App\Http\Controllers\Api;

use App\Models\Brand;
use App\Models\Color;
use App\Models\Country;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use App\Models\CustomerCarePage;
use App\Models\CategoryAttribute;
use App\Http\Controllers\Controller;
use PHPUnit\Framework\Constraint\Count;

class ProductFormController extends Controller
{

    public function getCustomerCare()
    {
        $page=CustomerCarePage::where('status',1)->get();
        return response()->json([
            'data' => $page,
            'msg'=>'Customer Care Data'
        ], 200);
    }
    public function categories(Request $request)
    {
        $category = Category::where('status',1)->withCount(['ancestors', 'descendants'])
            ->get();

        return response()->json([
            'allCategory' => $category,
        ], 200);
    }

    public function getSelectedColors(Request $request)
    {
       
        $colors=Color::whereIn('id',$request->colorId)->get();
        return response()->json($colors, 200);
    }

    public function getAllAncestors(Request $request)
    {
        return Category::withCount('ancestors')->with('children')->find($request->id);
    }

    public function brands()
    {
        return Brand::orderBy('name')->where('status',1)->get();
    }

    public function countries()
    {
        return Country::orderBy('name')->get();
    }
    public function getColors()
    {
        $colors = Color::where('status',1)->latest()->get();
        return response()->json($colors, 200);
    }
    public function getAttributes($id)
    {
        $attributes = CategoryAttribute::where('category_id', $id)->where('stock', 1)->get();
        return response()->json($attributes, 200);
    }
    public function getFeaturedImage($id)
    {
        $images = ProductImage::where('product_id', $id)->where('color_id', null)->get();
        return response()->json($images, 200);
    }
    public function getProduct($id)
    {
        $product = Product::find($id);
        return response()->json($product);
    }
    public function getProductAttribute($id)
    {
        $product = Product::find($id);
        $attributes = $product->attributes;
        return response()->json($attributes, 200);
    }

    public function getProductColor($id)
    {
        $color = ProductImage::where('product_id', $id)->where('color_id', '!=', null)->get();
        return response()->json($color, 200);
    }
}
