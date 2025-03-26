<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Brand;
use App\Models\Color;
use App\Models\Product;
use App\Models\Category;
use Jenssegers\Agent\Agent;
use Illuminate\Http\Request;
use App\Helpers\PaginationHelper;
use App\Models\Admin\SearchKeyword;
use App\Http\Controllers\Controller;
use App\Enum\Product\ProductStatusEnum;
use App\Models\City;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Pagination\Paginator;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        // dd($request->all());
        // $validator = Validator::make($request->all(), [
        //     'search' => 'required',
        // ]);

        // if ($validator->fails()) {
        //     $request->session()->flash('error', 'OOPs, Search field is required');
        //     return back();
        // }
        // // Location of User-----------------------------------------------------------------------------
        $ip_address = $request->ip();
        $address_info = City::find(146);
        $full_address =  $address_info->city_name;

        // Browser Info----------------------------------------------------------------------------------
        $browser_info = new Agent();
        if ($browser_info->isRobot()) {
            $browser_info = 'The Customer is Robot.';
        } else {
            $browser = $browser_info->browser();
            $system = $browser_info->platform();
        }
        $customer = auth()->guard('customer')->user();
        $ip_address = $request->ip();

        $data = [
            'customer_id' => $customer->id ?? 1,
            'search_keyword' => $request->search,
            'ip_address' => $ip_address,
            'mac_address' => exec('getmac'),
            'browser' => $browser,
            'system' => $system,
            'full_address' => $full_address,
        ];
        try {
            $saved_keyword = SearchKeyword::create($data);
        } catch (\Throwable $th) {
            return back();
            $request->session()->flash('error', 'OOPs, Please Try again.');
        }
        // success store of keyword-------------------------------------------------------------------------------
        $wholeSeller=auth()->guard('customer')->user() ? auth()->guard('customer')->user()->wholeseller : null;
        $slug = $request->search;
        $products = Product::where('name', 'like', '%' . $slug . '%')->where('status',1)->where('publishStatus', ProductStatusEnum::ACTIVE)->get();
        if(!$wholeSeller || $wholeSeller !=true){
            $products=$products->where('product_for','!=','2');
        }else{
            $products=$products->where('product_for','!=','1');
        }
        $brand_id=[];
        $color_id=[];
        foreach($products as $product)
        {
            
           if($product->brand_id !=null)
           {
                $brand_id[]=$product->brand_id;
           }
           foreach($product->stocks as $data)
           {
                $color_id[]=$data->color_id;
           }
        }
        $brand_id=collect($brand_id)->unique();
        $color_id=collect($color_id)->unique();
        
        $colors = Color::whereIn('id',$color_id)->get();
        $brands = Brand::whereIn('id',$brand_id)->get();
        $url = url('home-search/?search='.$request->search);
        $products = PaginationHelper::paginate(collect($products), 40)->withPath($url);
       
        return view('frontend.search.search', compact('products', 'colors', 'brands', 'slug'));
    }

    // public function finalSearch(Request $request)
    // {
    //     $search=session('voice-search');
        
    //     // $validator = Validator::make($request->all(), [
    //     //     'search' => 'required',
    //     // ]);

    //     // if ($validator->fails()) {
    //     //     $request->session()->flash('error', 'OOPs, Search field is required');
    //     //     return back();
    //     // }
    //     // // Location of User-----------------------------------------------------------------------------
    //     $ip_address = $request->ip();
    //     $address_info = Location::get($ip_address);
    //     $full_address =  @$address_info->cityName . ", " . @$address_info->regionName . ", " . @$address_info->countryName;

    //     // Browser Info----------------------------------------------------------------------------------
    //     $browser_info = new Agent();
    //     if ($browser_info->isRobot()) {
    //         $browser_info = 'The Customer is Robot.';
    //     } else {
    //         $browser = $browser_info->browser();
    //         $system = $browser_info->platform();
    //     }
    //     $customer = auth()->guard('customer')->user();
    //     $ip_address = $request->ip();
        
    //     $data = [
    //         'customer_id' => $customer->id ?? 1,
    //         'search_keyword' => $request->search,
    //         'ip_address' => $ip_address,
    //         'mac_address' => exec('getmac'),
    //         'browser' => $browser,
    //         'system' => $system,
    //         'full_address' => $full_address,
    //     ];
    //     // try {
    //     //     $saved_keyword = SearchKeyword::create($data);
    //     //     dd('sanu ma');
    //     // } catch (\Throwable $th) {
    //     //     return back();
    //     //     $request->session()->flash('error', 'OOPs, Please Try again.');
    //     // }
    //     // success store of keyword-------------------------------------------------------------------------------
    //     $slug = $search;
    //     $colors = Color::get();
    //     $brands = Brand::get();
    //     $products = Product::where('name', 'like', '%' . $slug . '%')->where('status',1)->where('publishStatus', ProductStatusEnum::ACTIVE)->get();
    //     return view('frontend.search.search', compact('products', 'colors', 'brands', 'slug'));
    // }

    public function voiceSearch(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'search_data' => 'required',
        ]);

        if ($validator->fails()) {
            $request->session()->flash('error', 'OOPs, Search field is required');
            return back();
        }
        // // Location of User-----------------------------------------------------------------------------
        $ip_address = $request->ip();
        // $address_info = Location::get($ip_address);
        // $full_address =  @$address_info->cityName . ", " . @$address_info->regionName . ", " . @$address_info->countryName;

        // Browser Info----------------------------------------------------------------------------------
        $browser_info = new Agent();
        if ($browser_info->isRobot()) {
            $browser_info = 'The Customer is Robot.';
        } else {
            $browser = $browser_info->browser();
            $system = $browser_info->platform();
        }
        $customer = auth()->guard('customer')->user();
        $ip_address = $request->ip();

        $data = [
            'customer_id' => $customer->id ?? 1,
            'search_keyword' => $request->search_data,
            'ip_address' => $ip_address,
            'mac_address' => exec('getmac'),
            'browser' => $browser,
            'system' => $system,
            // 'full_address' => $full_address,
        ];
        try {
            $saved_keyword = SearchKeyword::create($data);
        } catch (\Throwable $th) {
            return back();
            $request->session()->flash('error', 'OOPs, Please Try again.');
        }
        // success store of keyword-------------------------------------------------------------------------------
        
        $slug = $request->search_data;
        $colors = Color::get();
        $brands = Brand::get();
        $products = Product::where('name', 'like', '%' . $slug . '%')->where('status',1)->where('publishStatus', ProductStatusEnum::ACTIVE)->get();
        return view('frontend.search.search', compact('products', 'colors', 'brands', 'slug'));
    }
    // public function search(Request $request){
    //     // dd($request->all());
    //     $request->session()->forget('search_item');
    //     $colors = Color::get();
    //     $brands = Brand::get();
    //     $search_text = $request->get('search');
    //     $products = Product::where('name', 'like', '%'. $search_text . '%')
    //                 ->get();
    //     return view('frontend.search', compact('search_text','products','colors','brands'));

    // }

    // public function autoComplete(Request $request){
    //     $data = Product::select("name")
    //                 ->where('name', 'like', '%'. $request->get('query'). '%')
    //                 ->get();
    //     return response()->json($data);
    // }

    // public function searchFilterData(Request $request){


    //     $products = [];
    //     $data_sort = $request->data_sort;
    //     $search_text = $request->search_text;
    //     $paginate = $request->paginate;
    //     $color_id = $request->color_id;
    //     $brand_id = $request->brand_id;
    //     $slug = $request->slug;
    //     $min_price = $request->min_price;
    //     $max_price = $request->max_price;
    //     if($paginate == null){
    //         $paginate = 30;
    //     }
    //     if($min_price != null && $max_price === null){
    //         $max_price = 10000000;
    //     }
    //     elseif($min_price === null && $max_price != null){
    //         $min_price = 0;
    //     }
    //     elseif($min_price != null && $max_price != null){
    //         $min_price = $min_price;
    //         $max_price = $max_price;
    //     }
    //     else{
    //         $min_price = $min_price;
    //         $max_price = $max_price;
    //     }




    //     $price = array($min_price, $max_price);

    //     if($color_id != null && $brand_id != null ){
    //         if( $max_price != null){
    //             $colorWithBrand_products = Product::with('images','category','features','brand','colors','sizes','stocks','stockways','productTags','attributes','dangerousGoods')
    //                                     ->whereIn('brand_id',$brand_id)
    //                                     ->whereHas('images',function($q) use($color_id){
    //                                         $q->whereIn('color_id', $color_id);
    //                                     })
    //                                     ->whereHas('stocks',function($q) use($price){
    //                                         $q->whereBetween('price',$price);
    //                                     })->get();
    //             $result = Product::where('name', 'ilike', '%'. $search_text . '%')->get();
    //             $category_products = [];
    //             foreach($result as $pro)
    //             {
    //                 array_push($category_products,$pro->id);
    //             }
    //             foreach($colorWithBrand_products as $product){
    //                 if(in_array($product->id, $category_products)){
    //                     array_push($products,$product);
    //                 }
    //             }

    //         }
    //         else{
    //             $colorWithBrand_products = Product::with('images','category','features','brand','colors','sizes','stocks','stockways','productTags','attributes','dangerousGoods')
    //                                     ->whereIn('brand_id',$brand_id)
    //                                     ->whereHas('images',function($q) use($color_id){
    //                                         $q->whereIn('color_id', $color_id);
    //                                     })->get();
    //             $result = Product::where('name', 'ilike', '%'. $search_text . '%')->get();
    //             $category_products = [];
    //             foreach($result as $pro)
    //             {
    //                 array_push($category_products,$pro->id);
    //             }
    //             foreach($colorWithBrand_products as $product){
    //                 if(in_array($product->id, $category_products)){
    //                     array_push($products,$product);
    //                 }
    //             }

    //             }
    //     }

    //     elseif($color_id != null && $brand_id === null ){
    //         if( $max_price != null){
    //             $colorPrice_products = Product::with('images','category','features','brand','colors','sizes','stocks','stockways','productTags','attributes','dangerousGoods')->whereHas('images',function($q) use($color_id){
    //                                     $q->whereIn('color_id', $color_id);
    //                                     })
    //                                   ->whereHas('stocks',function($q) use($price){
    //                                         $q->whereBetween('price',$price);
    //                                         })
    //                                   ->get();
    //             $result = Product::where('name', 'ilike', '%'. $search_text . '%')->get();
    //             $category_products = [];
    //             foreach($result as $pro)
    //             {
    //                 array_push($category_products,$pro->id);
    //             }
    //             foreach($colorPrice_products as $product){
    //                 if(in_array($product->id, $category_products)){
    //                     array_push($products,$product);
    //                 }
    //             }

    //         }
    //         else{
    //             $color_products = Product::with('images','category','features','brand','colors','sizes','stocks','stockways','productTags','attributes','dangerousGoods')->whereHas('images',function($q) use($color_id){
    //                                 $q->whereIn('color_id', $color_id);
    //                                 })
    //                               ->get();
    //             $result = Product::where('name', 'ilike', '%'. $search_text . '%')->get();
    //             $category_products = [];
    //             foreach($result as $pro)
    //             {
    //                 array_push($category_products,$pro->id);
    //             }
    //             foreach($color_products as $product){
    //                 if(in_array($product->id, $category_products)){
    //                     array_push($products,$product);
    //                 }
    //             }

    //         }
    //     }


    //     elseif($color_id === null && $brand_id != null ){
    //         if( $max_price != null){
    //             $brand_products = Product::with('images','category','features','brand','colors','sizes','stocks','stockways','productTags','attributes','dangerousGoods')
    //                                 ->whereIn('brand_id',$brand_id)
    //                                 ->whereHas('stocks', function($q) use($price){
    //                                     $q->whereBetween('price',$price);
    //                                 })
    //                                 ->get();
    //             $result = Product::where('name', 'ilike', '%'. $search_text . '%')->get();
    //             $category_products = [];
    //             foreach($result as $pro)
    //             {
    //                 array_push($category_products,$pro->id);
    //             }
    //             foreach($brand_products as $product){
    //                 if(in_array($product->id, $category_products)){
    //                     array_push($products,$product);
    //                 }

    //             }
    //             // return response()->json([
    //             //     'bishesh'=> 'products',
    //             // ]);

    //         }
    //         else{
    //             $brand_products = Product::whereIn('brand_id',$brand_id)->get();
    //             $result = Product::where('name', 'ilike', '%'. $search_text . '%')->get();
    //             $category_products = [];
    //             foreach($result as $pro)
    //             {
    //                 array_push($category_products,$pro->id);
    //             }
    //             foreach($brand_products as $product){
    //                 if(in_array($product->id, $category_products)){
    //                     array_push($products,$product);
    //                 }

    //             }
    //             // return response()->json([
    //             //     'bishesh'=> 'products',
    //             // ]);

    //         }

    //     }

    //     else{
    //         if( $max_price != null){
    //             $result = Product::where('name', 'ilike', '%'. $search_text . '%')->get();
    //             $category_products = [];
    //             foreach($result as $pro)
    //             {
    //                 array_push($category_products,$pro->id);
    //             }
    //             $price_products = Product::with('images','category','features','brand','colors','sizes','stocks','stockways','productTags','attributes','dangerousGoods')->whereHas('stocks',function($q) use($price){
    //                 $q->whereBetween('price', $price);
    //             })->get();

    //             foreach($price_products as $product){
    //                 if(in_array($product->id, $category_products)){
    //                     array_push($products,$product);
    //                 }
    //             }
    //             // return response()->json([
    //             //     'bishesh'=>"first testing",
    //             // ]);

    //         }
    //         else{
    //             $result = Product::where('name', 'ilike', '%'. $search_text . '%')->get();
    //             $category_products = [];
    //             foreach($result as $pro)
    //             {
    //                 array_push($category_products,$pro);

    //             }
    //             $products = $category_products;
    //         }
    //     }

    //     // return response()->json([
    //     //         'bishesh'=>$products,
    //     //     ]);

    //     if($data_sort!=null){
    //         switch ($data_sort) {
    //             case 'ASC':
    //                 $products = collect($products);
    //                 $products = $products->sortBy('name', SORT_NATURAL|SORT_FLAG_CASE);
    //             break;
    //             case "DESC":
    //                 $products = collect($products);
    //                 $products = $products->sortByDesc('name', SORT_NATURAL|SORT_FLAG_CASE);
    //             break;
    //             case "increasing":
    //                 $sorted_products = [];
    //                 foreach($products as $product){
    //                     if(isset($product->stocks->first()->special_price)){
    //                         $price = $product->stocks->first()->special_price;
    //                     }else{
    //                         $price = $product->stocks->first()->price;
    //                     }
    //                     $product->setAttribute('price',$price);
    //                     array_push($sorted_products, $product);
    //                 }
    //                 $products = collect($sorted_products);
    //                 $products = $products->sortBy('price');
    //             break;
    //             case "decreasing":
    //                 $sorted_products = [];
    //                 foreach($products as $product){
    //                     if(isset($product->stocks->first()->special_price)){
    //                         $price = $product->stocks->first()->special_price;
    //                     }else{
    //                         $price = $product->stocks->first()->price;
    //                     }
    //                     $product->setAttribute('price',$price);
    //                     array_push($sorted_products, $product);
    //                 }
    //                 $products = collect($sorted_products);
    //                 $products = $products->sortByDesc('price');
    //             break;
    //             case "recent":
    //                 $products = collect($products);
    //                 $products = $products->sortBy('created_at');
    //                 break;
    //             case "old":
    //                 $products = collect($products);
    //                 $products = $products->sortByDesc('created_at');
    //                 break;
    //             default:
    //                 $products = collect($products);
    //                 $products = $products->sortByDesc('name');
    //         }
    //     }
    //     return view('frontend.category.color', compact('products','paginate'));
    // }

    // // public function paginate($items, $perPage = 20, $page = null, $options = [])
    // // {
    // //     $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
    // //     $items = $items instanceof Collection ? $items : Collection::make($items);
    // //     return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options = [
    // //         'path' => Paginator::resolveCurrentPath()
    // //     ]);
    // // }

    public function liveSearch()
    {
        $products = Product::all();
    }
}
