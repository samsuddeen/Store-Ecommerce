<?php

namespace App\Http\Controllers\Ajax;

use Illuminate\Pagination\LengthAwarePaginator;
use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;
use Illuminate\Pagination\Paginator;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Tag;
use Illuminate\Support\Facades\Validator;
class TagFilterController extends Controller
{
    protected $folderName = 'frontend.';
    public function product(Request $request){
        $products = [];
        $data_sort = $request->data_sort;
        $paginate = $request->paginate;
        $color_id = $request->color_id;
        $brand_id = $request->brand_id;
        $slug = $request->slug;
        $min_price = $request->min_price;
        $max_price = $request->max_price;
        if($paginate == null){
            $paginate = 30;
        }
        if($min_price != null && $max_price === null){
            $max_price = 10000000;
        }
        elseif($min_price === null && $max_price != null){
            $min_price = 0;
        }
        elseif($min_price != null && $max_price != null){
            $min_price = $min_price;
            $max_price = $max_price;
        }
        else{
            $min_price = $min_price;
            $max_price = $max_price;
        }

        $price = array($min_price, $max_price);

        if($color_id != null && $brand_id != null ){
            if( $max_price != null){
                $colorWithBrand_products = Product::with('images','category','features','brand','colors','sizes','stocks','stockways','productTags','attributes','dangerousGoods')
                                        ->whereIn('brand_id',$brand_id)
                                        ->whereHas('images',function($q) use($color_id){
                                            $q->whereIn('color_id', $color_id);
                                        })
                                        ->whereHas('stocks',function($q) use($price){
                                            $q->whereBetween('price',$price);
                                        })->get();

                $tag = Tag::where('slug',$slug)->get();

                $tag_products = [];
                foreach($tag as $tags){
                    foreach($tags->products as $pro){
                        array_push($tag_products,$pro->id);
                    }
                }
                foreach($colorWithBrand_products as $product){
                    if(in_array($product->id, $tag_products)){
                        array_push($products,$product);
                    }
                }

            }
            else{
                $colorWithBrand_products = Product::with('images','category','features','brand','colors','sizes','stocks','stockways','productTags','attributes','dangerousGoods')
                                        ->whereIn('brand_id',$brand_id)
                                        ->whereHas('images',function($q) use($color_id){
                                            $q->whereIn('color_id', $color_id);
                                        })->get();
                $tag = Tag::where('slug',$slug)->get();

                $tag_products = [];
                foreach($tag as $tags){
                    foreach($tags->products as $pro){
                        array_push($tag_products,$pro->id);
                    }
                }
                foreach($colorWithBrand_products as $product){
                    if(in_array($product->id, $tag_products)){
                        array_push($products,$product);
                    }
                }

                }
        }

        elseif($color_id != null && $brand_id === null ){
            if( $max_price != null){
                $colorPrice_products = Product::with('images','category','features','brand','colors','sizes','stocks','stockways','productTags','attributes','dangerousGoods')->whereHas('images',function($q) use($color_id){
                                        $q->whereIn('color_id', $color_id);
                                        })
                                      ->whereHas('stocks',function($q) use($price){
                                            $q->whereBetween('price',$price);
                                            })
                                      ->get();
                $tag = Tag::where('slug',$slug)->get();

                $tag_products = [];
                foreach($tag as $tags){
                    foreach($tags->products as $pro){
                        array_push($tag_products,$pro->id);
                    }
                }
                foreach($colorPrice_products as $product){
                    if(in_array($product->id, $tag_products)){
                        array_push($products,$product);
                    }
                }

            }
            else{
                $color_products = Product::with('images','category','features','brand','colors','sizes','stocks','stockways','productTags','attributes','dangerousGoods')->whereHas('images',function($q) use($color_id){
                                    $q->whereIn('color_id', $color_id);
                                    })
                                  ->get();
                $tag = Tag::where('slug',$slug)->get();

                $tag_products = [];
                foreach($tag as $tags){
                    foreach($tags->products as $pro){
                        array_push($tag_products,$pro->id);
                    }
                }
                foreach($color_products as $product){
                    if(in_array($product->id, $tag_products)){
                        array_push($products,$product);
                    }
                }

            }
        }


        elseif($color_id === null && $brand_id != null ){
            if( $max_price != null){
                $brand_products = Product::with('images','category','features','brand','colors','sizes','stocks','stockways','productTags','attributes','dangerousGoods')
                                    ->whereIn('brand_id',$brand_id)
                                    ->whereHas('stocks', function($q) use($price){
                                        $q->whereBetween('price',$price);
                                    })
                                    ->get();
                $tag = Tag::where('slug',$slug)->get();

                $tag_products = [];
                foreach($tag as $tags){
                    foreach($tags->products as $pro){
                        array_push($tag_products,$pro->id);
                    }
                }
                foreach($brand_products as $product){
                    if(in_array($product->id, $tag_products)){
                        array_push($products,$product);
                    }

                }
                // return response()->json([
                //     'bishesh'=> 'products',
                // ]);

            }
            else{
                $brand_products = Product::whereIn('brand_id',$brand_id)->get();
                $tag = Tag::where('slug',$slug)->get();

                $tag_products = [];
                foreach($tag as $tags){
                    foreach($tags->products as $pro){
                        array_push($tag_products,$pro->id);
                    }
                }
                foreach($brand_products as $product){
                    if(in_array($product->id, $tag_products)){
                        array_push($products,$product);
                    }

                }
                // return response()->json([
                //     'bishesh'=> 'products',
                // ]);

            }

        }

        else{
            if( $max_price != null){
                $tag = Tag::where('slug',$slug)->get();

                $tag_products = [];
                foreach($tag as $tags){
                    foreach($tags->products as $pro){
                        array_push($tag_products,$pro->id);
                    }
                }
                $price_products = Product::with('images','category','features','brand','colors','sizes','stocks','stockways','productTags','attributes','dangerousGoods')->whereHas('stocks',function($q) use($price){
                    $q->whereBetween('price', $price);
                })->get();

                foreach($price_products as $product){
                    if(in_array($product->id, $tag_products)){
                        array_push($products,$product);
                    }
                }
                // return response()->json([
                //     'bishesh'=>"first testing",
                // ]);

            }
            else{
                $tag = Tag::where('slug',$slug)->get();
                $tag_products = [];
                foreach($tag as $tags){
                    foreach($tags->products as $pro){
                        array_push($tag_products,$pro->id);
                    }
                }
                $products = $tag_products;
            }
        }
        if($data_sort!=null){
            switch ($data_sort) {
                case 'ASC':
                    $products = collect($products);
                    $products = $products->sortBy('name', SORT_NATURAL|SORT_FLAG_CASE);
                break;
                case "DESC":
                    $products = collect($products);
                    $products = $products->sortByDesc('name', SORT_NATURAL|SORT_FLAG_CASE);
                break;
                case "increasing":
                    $sorted_products = [];
                    foreach($products as $product){
                        if(isset($product->stocks->first()->special_price)){
                            $price = $product->stocks->first()->special_price;
                        }else{
                            $price = $product->stocks->first()->price;
                        }
                        $product->setAttribute('price',$price);
                        array_push($sorted_products, $product);
                    }
                    $products = collect($sorted_products);
                    $products = $products->sortBy('price');
                break;
                case "decreasing":
                    $sorted_products = [];
                    foreach($products as $product){
                        if(isset($product->stocks->first()->special_price)){
                            $price = $product->stocks->first()->special_price;
                        }else{
                            $price = $product->stocks->first()->price;
                        }
                        $product->setAttribute('price',$price);
                        array_push($sorted_products, $product);
                    }
                    $products = collect($sorted_products);
                    $products = $products->sortByDesc('price');
                break;
                case "recent":
                    $products = collect($products);
                    $products = $products->sortBy('created_at');
                    break;
                case "old":
                    $products = collect($products);
                    $products = $products->sortByDesc('created_at');
                    break;
                default:
                    $products = collect($products);
                    $products = $products->sortByDesc('name');
            }
        }
        return view($this->folderName . 'category.color', compact('products','paginate'));
    }

    public function sortTagItem(Request $request)
    {   
        $validator = Validator::make($request->all(), [
            'sort_by'=>'required',
            'slug'=>'required|string'
        ]);
        if ($validator->fails()) {
            $response = [
                'error' => true,
                'data' => null,
                'message' => $validator->errors()
            ];
            return response()->json($response, 200);
        }
        
        $tag=Tag::where('slug',$request->slug)->first();
       

        
        $product=$tag->products;
        $products=null;

        
        switch($request->sort_by)
        {
            case 'ASC':
            $products=collect($product);
            $products=$product->sortBy('name',SORT_NATURAL|SORT_FLAG_CASE);
            break;

            case 'DESC':
            $products=collect($product);
            $products=$product->sortByDesc('name',SORT_NATURAL|SORT_FLAG_CASE);
            break;

            case 'increasing':
            foreach($product as $p)
            {
                $offer_price=getOfferProduct($p,$p->stocks[0]);
                if($offer_price !=null)
                {
                    $price=$offer_price;
                }
                elseif($p->getPrice->special_price)
                {
                    $price=$p->getPrice->special_price;
                }
                else
                {
                    $price=$p->getPrice->price;
                }
                $p->setAttribute('price',$price);
            }
            $products=$product->sortBy('price',SORT_NATURAL|SORT_FLAG_CASE);
            break;

            case 'decreasing':
            foreach($product as $p)
            {
                $offer_price=getOfferProduct($p,$p->stocks[0]);
                if($offer_price !=null)
                {
                    $price=$offer_price;
                }
                elseif($p->getPrice->special_price)
                {
                    $price=$p->getPrice->special_price;
                }
                else
                {
                    $price=$p->getPrice->price;
                }
                $p->setAttribute('price',$price);
            }
            $products=$product->sortByDesc('price',SORT_NATURAL|SORT_FLAG_CASE);
            break;

            case 'recent':
            $products=collect($product);
            $products=$product->sortBy('created_at',SORT_NATURAL|SORT_FLAG_CASE);
            break;

            case 'old':
            $products=collect($product);
            $products=$product->sortByDesc('created_at',SORT_NATURAL|SORT_FLAG_CASE);
            break;
        }
        if($request->paginate_value==='all')
        {
            $products=$products;
        }
        else
        {
            $products=$products->take($request->paginate_value);
        }
        
        return view($this->folderName . 'category.color', compact('products'));
    }

    public function paginateItem(Request $request)
    {
        $tag=Tag::where('slug',$request->slug)->first();
        
       
       
        $product=$tag->products;
        switch($request->sort)
        {
            case 'Select option':
            $products=collect($product);
            $products=$product;
            break;

            case 'ASC':
            $products=collect($product);
            $products=$product->sortBy('name',SORT_NATURAL|SORT_FLAG_CASE);
            break;

            case 'DESC':
            $products=collect($product);
            $products=$product->sortByDesc('name',SORT_NATURAL|SORT_FLAG_CASE);
            break;

            case 'increasing':
            foreach($product as $p)
            {
                $offer_price=getOfferProduct($p,$p->stocks[0]);
                if($offer_price !=null)
                {
                    $price=$offer_price;
                }
                elseif($p->getPrice->special_price)
                {
                    $price=$p->getPrice->special_price;
                }
                else
                {
                    $price=$p->getPrice->price;
                }
                $p->setAttribute('price',$price);
            }
            $products=$product->sortBy('price',SORT_NATURAL|SORT_FLAG_CASE);
            break;

            case 'decreasing':
            foreach($product as $p)
            {
                $offer_price=getOfferProduct($p,$p->stocks[0]);
                if($offer_price !=null)
                {
                    $price=$offer_price;
                }
                elseif($p->getPrice->special_price)
                {
                    $price=$p->getPrice->special_price;
                }
                else
                {
                    $price=$p->getPrice->price;
                }
                $p->setAttribute('price',$price);
            }
            $products=$product->sortByDesc('price',SORT_NATURAL|SORT_FLAG_CASE);
            break;

            case 'recent':
            $products=collect($product);
            $products=$product->sortBy('created_at',SORT_NATURAL|SORT_FLAG_CASE);
            break;

            case 'old':
            $products=collect($product);
            $products=$product->sortByDesc('created_at',SORT_NATURAL|SORT_FLAG_CASE);
            break;
        }
        // $paginate=10;
        if($request->paginate_value==='all')
        {
            $products=$products;
        }
        else
        {
            $products=$products->take($request->paginate_value);
        }

        
        return view($this->folderName . 'category.color', compact('products'));

    }
}
