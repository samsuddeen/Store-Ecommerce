<?php

namespace App\Http\Controllers\Ajax;

use App\Models\Tag;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use App\Models\OrderAsset;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Helpers\PaginationHelper;
use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;
use App\Enum\Product\ProductStatusEnum;
use Illuminate\Pagination\LengthAwarePaginator;

class FilterController extends Controller
{
    protected $folderName = 'frontend.';


    public function searchPaginateData(Request $request)
    {
        dd($request->all());
        return response()->json(200);
    }


    public function newMultipleFilter(Request $request)
    {
        $sort_by=$request->sort_by;
        $paginate=$request->paginate;
        $min_price=$request->min_price;
        $max_price=$request->max_price;
        $color_id=$request->color_id;
        $brand_id=$request->brand_id;
        $products=Product::with('images', 'category', 'features', 'brand', 'colors', 'sizes', 'stocks', 'stockways', 'productTags', 'attributes', 'dangerousGoods')->where('status', 1)->where('publishStatus', '1')->orderBy('created_at', 'DESC')->get();
        $tag_products=$products;
        if($min_price==null && $max_price !=null)
        {
            $min_price=1;
            $max_price=$max_price;
        }
        elseif($min_price ==null && $max_price ==null || $min_price==0 && $max_price==0)
        {
            $min_price=null;
            $max_price=null;
        }
        else
        {
            $min_price=$min_price;
            $max_price=$max_price;
        }
        $price=array($min_price,$max_price);
        if($color_id !=null && $brand_id !=null)
        {
           if($max_price !=null)
           {
               
                $all_product=Product::with('images','category','features','brand','colors','sizes','stocks','stockways','productTags','attributes','dangerousGoods')
                                    ->whereIn('brand_id',$brand_id)
                                    ->whereHas('stocks',function($q) use ($color_id){
                                        return $q->whereIn('color_id',$color_id);
                                    })
                                    ->whereHas('stocks',function($q) use ($price){
                                        return $q->whereBetween('price',$price);
                                    })->get();
           }
           else
           {
                $all_product=Product::with('images','category','features','brand','colors','sizes','stocks','stockways','productTags','attributes','dangerousGoods')
                                    ->whereIn('brand_id',$brand_id)
                                    ->whereHas('stocks',function($q) use ($color_id){
                                        return $q->whereIn('color_id',$color_id);
                                    })->get();
           }

        }
        elseif($color_id==null && $brand_id !=null)
        {
            if($max_price !=null)
            {
                $all_product=Product::with('images','category','features','brand','colors','sizes','stocks','stockways','productTags','attributes','dangerousGoods')
                                    ->whereIn('brand_id',$brand_id)
                                    ->whereHas('stocks',function($q) use ($price){
                                        return $q->whereBetween('price',$price);
                                    })
                                    ->get();
            }
            else
            {
               $all_product=Product::with('images','category','features','brand','colors','sizes','stocks','stockways','productTags','attributes','dangerousGoods')
                                    ->whereIn('brand_id',$brand_id)
                                    ->get();
            }

        }
        elseif($color_id !=null && $brand_id==null)
        {
            if($max_price !=null)
            {
                $all_product=Product::with('images','category','features','brand','colors','sizes','stocks','stockways','productTags','attributes','dangerousGoods')
                                    ->whereHas('stocks',function($q) use ($color_id)
                                    {
                                        return $q->whereIn('color_id',$color_id);
                                    })
                                    ->whereHas('stocks',function($q) use ($price){
                                        return $q->whereBetween('price',$price);
                                    })
                                    ->get();
            }
            else
            {
               $all_product=Product::with('images','category','features','brand','colors','sizes','stocks','stockways','productTags','attributes','dangerousGoods')
                                    ->whereHas('stocks',function($q) use ($color_id){
                                        return $q->whereIn('color_id',$color_id);
                                    })
                                    ->get();
            }
        }
        else
        {
            if($max_price !=null)
            {
                $all_product=Product::with('images','category','features','brand','colors','sizes','stocks','stockways','productTags','attributes','dangerousGoods')
                                    ->whereHas('stocks',function($q) use ($price)
                                    {
                                        return $q->whereBetween('price',$price);
                                    })
                                    ->get();
            }
            else
            {
                $all_product=Product::with('images','category','features','brand','colors','sizes','stocks','stockways','productTags','attributes','dangerousGoods')
                                    ->get();
            }
        }
        $tag_product=[];
        foreach($tag_products as $p)
        {
            foreach($all_product as $data)
            {
                if($data->id===$p->id)
                {
                    $tag_product[]=$p;
                }
            }
        }
        switch($sort_by)
        {
            case 'Select option':
                $final_product=$tag_product;
                break;
            case 'ASC':
                
                $final_product=collect($tag_product)->sortBy('name',SORT_NATURAL|SORT_FLAG_CASE);
                break;
            case 'DESC':
                $final_product=collect($tag_product)->sortByDesc('name',SORT_NATURAL|SORT_FLAG_CASE);
                break;
            case 'increasing':
                foreach($tag_product as $p)
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
                $final_product=collect($tag_product)->sortBy('price',SORT_NATURAL|SORT_FLAG_CASE);
                break;
            case 'decreasing':
                foreach($tag_product as $p)
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
                $final_product=collect($tag_product)->sortByDesc('price',SORT_NATURAL|SORT_FLAG_CASE);
                break;
            case 'recent';
                $final_product=collect($tag_product)->sortBy('created_at',SORT_NATURAL|SORT_FLAG_CASE);
                break;
            default:
                $final_product=collect($tag_product)->sortByDesc('created_at',SORT_NATURAL|SORT_FLAG_CASE);
                break;
        }
        
        if($paginate==='all')
        {
            $show_products=$final_product;
        }
        else
        {
            $show_products=collect($final_product)->take($paginate);
        }

        $url=route('new-multiple-filter',$request->all());
        $show_products = PaginationHelper::paginate(collect($show_products), 40)->withPath($url);
        
        return view($this->folderName . 'category.color')
        ->with('products',$show_products);
    }

    public function newSpecialOfferMultipleFilter(Request $request){
        $sort_by=$request->sort_by;
        $paginate=$request->paginate;
        $min_price=$request->min_price;
        $max_price=$request->max_price;
        $color_id=$request->color_id;
        $brand_id=$request->brand_id;
        $products = Product::with('images', 'category', 'features', 'brand', 'colors', 'sizes', 'stocks', 'stockways', 'productTags', 'attributes', 'dangerousGoods')->where('status', 1)->where('publishStatus', '1')->orderBy('created_at', 'DESC')
        ->whereHas('category', function ($q) {
            return $q->where('slug', 'LIKE', '%' . 'special' . '%');
        })
        ->latest()
        ->get();

        $tag_products=$products;
        if($min_price==null && $max_price !=null)
        {
            $min_price=1;
            $max_price=$max_price;
        }
        elseif($min_price ==null && $max_price ==null || $min_price==0 && $max_price==0)
        {
            $min_price=null;
            $max_price=null;
        }
        else
        {
            $min_price=$min_price;
            $max_price=$max_price;
        }
        $price=array($min_price,$max_price);
        if($color_id !=null && $brand_id !=null)
        {
           if($max_price !=null)
           {
               
                $all_product=Product::with('images','category','features','brand','colors','sizes','stocks','stockways','productTags','attributes','dangerousGoods')
                                    ->whereIn('brand_id',$brand_id)
                                    ->whereHas('stocks',function($q) use ($color_id){
                                        return $q->whereIn('color_id',$color_id);
                                    })
                                    ->whereHas('stocks',function($q) use ($price){
                                        return $q->whereBetween('price',$price);
                                    })->get();
           }
           else
           {
                $all_product=Product::with('images','category','features','brand','colors','sizes','stocks','stockways','productTags','attributes','dangerousGoods')
                                    ->whereIn('brand_id',$brand_id)
                                    ->whereHas('stocks',function($q) use ($color_id){
                                        return $q->whereIn('color_id',$color_id);
                                    })->get();
           }

        }
        elseif($color_id==null && $brand_id !=null)
        {
            if($max_price !=null)
            {
                $all_product=Product::with('images','category','features','brand','colors','sizes','stocks','stockways','productTags','attributes','dangerousGoods')
                                    ->whereIn('brand_id',$brand_id)
                                    ->whereHas('stocks',function($q) use ($price){
                                        return $q->whereBetween('price',$price);
                                    })
                                    ->get();
            }
            else
            {
               $all_product=Product::with('images','category','features','brand','colors','sizes','stocks','stockways','productTags','attributes','dangerousGoods')
                                    ->whereIn('brand_id',$brand_id)
                                    ->get();
            }

        }
        elseif($color_id !=null && $brand_id==null)
        {
            if($max_price !=null)
            {
                $all_product=Product::with('images','category','features','brand','colors','sizes','stocks','stockways','productTags','attributes','dangerousGoods')
                                    ->whereHas('stocks',function($q) use ($color_id)
                                    {
                                        return $q->whereIn('color_id',$color_id);
                                    })
                                    ->whereHas('stocks',function($q) use ($price){
                                        return $q->whereBetween('price',$price);
                                    })
                                    ->get();
            }
            else
            {
               $all_product=Product::with('images','category','features','brand','colors','sizes','stocks','stockways','productTags','attributes','dangerousGoods')
                                    ->whereHas('stocks',function($q) use ($color_id){
                                        return $q->whereIn('color_id',$color_id);
                                    })
                                    ->get();
            }
        }
        else
        {
            if($max_price !=null)
            {
                $all_product=Product::with('images','category','features','brand','colors','sizes','stocks','stockways','productTags','attributes','dangerousGoods')
                                    ->whereHas('stocks',function($q) use ($price)
                                    {
                                        return $q->whereBetween('price',$price);
                                    })
                                    ->get();
            }
            else
            {
                $all_product=Product::with('images','category','features','brand','colors','sizes','stocks','stockways','productTags','attributes','dangerousGoods')
                                    ->get();
            }
        }
        $tag_product=[];
        foreach($tag_products as $p)
        {
            foreach($all_product as $data)
            {
                if($data->id===$p->id)
                {
                    $tag_product[]=$p;
                }
            }
        }
        switch($sort_by)
        {
            case 'Select option':
                $final_product=$tag_product;
                break;
            case 'ASC':
                
                $final_product=collect($tag_product)->sortBy('name',SORT_NATURAL|SORT_FLAG_CASE);
                break;
            case 'DESC':
                $final_product=collect($tag_product)->sortByDesc('name',SORT_NATURAL|SORT_FLAG_CASE);
                break;
            case 'increasing':
                foreach($tag_product as $p)
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
                $final_product=collect($tag_product)->sortBy('price',SORT_NATURAL|SORT_FLAG_CASE);
                break;
            case 'decreasing':
                foreach($tag_product as $p)
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
                $final_product=collect($tag_product)->sortByDesc('price',SORT_NATURAL|SORT_FLAG_CASE);
                break;
            case 'recent';
                $final_product=collect($tag_product)->sortBy('created_at',SORT_NATURAL|SORT_FLAG_CASE);
                break;
            default:
                $final_product=collect($tag_product)->sortByDesc('created_at',SORT_NATURAL|SORT_FLAG_CASE);
                break;
        }
        
        if($paginate==='all')
        {
            $show_products=$final_product;
        }
        else
        {
            $show_products=collect($final_product)->take($paginate);
        }

        $url=route('new-multiple-filter',$request->all());
        $show_products = PaginationHelper::paginate(collect($show_products), 40)->withPath($url);
        
        return view($this->folderName . 'category.color')
        ->with('products',$show_products);
    }
    public function popularMultipleFilter(Request $request)
    {
       
        $sort_by=$request->sort_by;
        $paginate=$request->paginate;
        $min_price=$request->min_price;
        $max_price=$request->max_price;
        $color_id=$request->color_id;
        $brand_id=$request->brand_id;
        $order_asset=OrderAsset::get();
        $order_product=$order_asset->map(function($item){
            return $item->product_id;
        });
        $final_product=collect($order_product)->unique();
        $products=Product::whereIn('id',$final_product)->with('images', 'category', 'features', 'brand', 'colors', 'sizes', 'stocks', 'stockways', 'productTags', 'attributes', 'dangerousGoods')->where('status', '1')->where('publishStatus', 1)->get();
        
        $tag_products=$products;
        if($min_price==null && $max_price !=null)
        {
            $min_price=1;
            $max_price=$max_price;
        }
        elseif($min_price ==null && $max_price ==null || $min_price==0 && $max_price==0)
        {
            $min_price=null;
            $max_price=null;
        }
        else
        {
            $min_price=$min_price;
            $max_price=$max_price;
        }
        $price=array($min_price,$max_price);
        if($color_id !=null && $brand_id !=null)
        {
           if($max_price !=null)
           {
               
                $all_product=Product::with('images','category','features','brand','colors','sizes','stocks','stockways','productTags','attributes','dangerousGoods')
                                    ->whereIn('brand_id',$brand_id)
                                    ->whereHas('stocks',function($q) use ($color_id){
                                        return $q->whereIn('color_id',$color_id);
                                    })
                                    ->whereHas('stocks',function($q) use ($price){
                                        return $q->whereBetween('price',$price);
                                    })->get();
           }
           else
           {
                $all_product=Product::with('images','category','features','brand','colors','sizes','stocks','stockways','productTags','attributes','dangerousGoods')
                                    ->whereIn('brand_id',$brand_id)
                                    ->whereHas('stocks',function($q) use ($color_id){
                                        return $q->whereIn('color_id',$color_id);
                                    })->get();
           }

        }
        elseif($color_id==null && $brand_id !=null)
        {
            if($max_price !=null)
            {
                $all_product=Product::with('images','category','features','brand','colors','sizes','stocks','stockways','productTags','attributes','dangerousGoods')
                                    ->whereIn('brand_id',$brand_id)
                                    ->whereHas('stocks',function($q) use ($price){
                                        return $q->whereBetween('price',$price);
                                    })
                                    ->get();
            }
            else
            {
               $all_product=Product::with('images','category','features','brand','colors','sizes','stocks','stockways','productTags','attributes','dangerousGoods')
                                    ->whereIn('brand_id',$brand_id)
                                    ->get();
            }

        }
        elseif($color_id !=null && $brand_id==null)
        {
            if($max_price !=null)
            {
                $all_product=Product::with('images','category','features','brand','colors','sizes','stocks','stockways','productTags','attributes','dangerousGoods')
                                    ->whereHas('stocks',function($q) use ($color_id)
                                    {
                                        return $q->whereIn('color_id',$color_id);
                                    })
                                    ->whereHas('stocks',function($q) use ($price){
                                        return $q->whereBetween('price',$price);
                                    })
                                    ->get();
            }
            else
            {
               $all_product=Product::with('images','category','features','brand','colors','sizes','stocks','stockways','productTags','attributes','dangerousGoods')
                                    ->whereHas('stocks',function($q) use ($color_id){
                                        return $q->whereIn('color_id',$color_id);
                                    })
                                    ->get();
            }
        }
        else
        {
            if($max_price !=null)
            {
                $all_product=Product::with('images','category','features','brand','colors','sizes','stocks','stockways','productTags','attributes','dangerousGoods')
                                    ->whereHas('stocks',function($q) use ($price)
                                    {
                                        return $q->whereBetween('price',$price);
                                    })
                                    ->get();
            }
            else
            {
                $all_product=Product::with('images','category','features','brand','colors','sizes','stocks','stockways','productTags','attributes','dangerousGoods')
                                    ->get();
            }
        }
        $tag_product=[];
       
        foreach($tag_products as $p)
        {
            foreach($all_product as $data)
            {
                if($data->id===$p->id)
                {
                    $tag_product[]=$p;
                }
            }
        }
        switch($sort_by)
        {
            case 'Select option':
                $final_product=$tag_product;
                break;
            case 'ASC':
                
                $final_product=collect($tag_product)->sortBy('name',SORT_NATURAL|SORT_FLAG_CASE);
                break;
            case 'DESC':
                $final_product=collect($tag_product)->sortByDesc('name',SORT_NATURAL|SORT_FLAG_CASE);
                break;
            case 'increasing':
                foreach($tag_product as $p)
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
                $final_product=collect($tag_product)->sortBy('price',SORT_NATURAL|SORT_FLAG_CASE);
                break;
            case 'decreasing':
                foreach($tag_product as $p)
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
                $final_product=collect($tag_product)->sortByDesc('price',SORT_NATURAL|SORT_FLAG_CASE);
                break;
            case 'recent';
                $final_product=collect($tag_product)->sortBy('created_at',SORT_NATURAL|SORT_FLAG_CASE);
                break;
            default:
                $final_product=collect($tag_product)->sortByDesc('created_at',SORT_NATURAL|SORT_FLAG_CASE);
                break;
        }
        
        if($paginate==='all')
        {
            $show_products=$final_product;
        }
        else
        {
            $show_products=collect($final_product)->take($paginate);
        }

        $url=route('popular-multiple-filter',$request->all());
        $show_products = PaginationHelper::paginate(collect($show_products), 40)->withPath($url);


        return view($this->folderName . 'category.color')
        ->with('products',$show_products);
    }

    public function searchMultipleFilter(Request $request)
    {
        $sort_by=$request->sort_by;
        $paginate=$request->paginate;
        $min_price=$request->min_price;
        $max_price=$request->max_price;
        $color_id=$request->color_id;
        $brand_id=$request->brand_id;
        $tag_products=$request->session()->get('search_item');
        if($min_price==null && $max_price !=null)
        {
            $min_price=1;
            $max_price=$max_price;
        }
        elseif($min_price ==null && $max_price ==null || $min_price==0 && $max_price==0)
        {
            $min_price=null;
            $max_price=null;
        }
        else
        {
            $min_price=$min_price;
            $max_price=$max_price;
        }
        $price=array($min_price,$max_price);
        if($color_id !=null && $brand_id !=null)
        {
           if($max_price !=null)
           {
               
                $all_product=Product::with('images','category','features','brand','colors','sizes','stocks','stockways','productTags','attributes','dangerousGoods')
                                    ->whereIn('brand_id',$brand_id)
                                    ->whereHas('images',function($q) use ($color_id){
                                        return $q->whereIn('color_id',$color_id);
                                    })
                                    ->whereHas('stocks',function($q) use ($price){
                                        return $q->whereBetween('price',$price);
                                    })->get();
           }
           else
           {
                $all_product=Product::with('images','category','features','brand','colors','sizes','stocks','stockways','productTags','attributes','dangerousGoods')
                                    ->whereIn('brand_id',$brand_id)
                                    ->whereHas('images',function($q) use ($color_id){
                                        return $q->whereIn('color_id',$color_id);
                                    })->get();
           }

        }
        elseif($color_id==null && $brand_id !=null)
        {
            if($max_price !=null)
            {
                $all_product=Product::with('images','category','features','brand','colors','sizes','stocks','stockways','productTags','attributes','dangerousGoods')
                                    ->whereIn('brand_id',$brand_id)
                                    ->whereHas('stocks',function($q) use ($price){
                                        return $q->whereBetween('price',$price);
                                    })
                                    ->get();
            }
            else
            {
               $all_product=Product::with('images','category','features','brand','colors','sizes','stocks','stockways','productTags','attributes','dangerousGoods')
                                    ->whereIn('brand_id',$brand_id)
                                    ->get();
            }

        }
        elseif($color_id !=null && $brand_id==null)
        {
            if($max_price !=null)
            {
                $all_product=Product::with('images','category','features','brand','colors','sizes','stocks','stockways','productTags','attributes','dangerousGoods')
                                    ->whereHas('images',function($q) use ($color_id)
                                    {
                                        return $q->whereIn('color_id',$color_id);
                                    })
                                    ->whereHas('stocks',function($q) use ($price){
                                        return $q->whereBetween('price',$price);
                                    })
                                    ->get();
            }
            else
            {
               $all_product=Product::with('images','category','features','brand','colors','sizes','stocks','stockways','productTags','attributes','dangerousGoods')
                                    ->whereHas('images',function($q) use ($color_id){
                                        return $q->whereIn('color_id',$color_id);
                                    })
                                    ->get();
            }
        }
        else
        {
            if($max_price !=null)
            {
                $all_product=Product::with('images','category','features','brand','colors','sizes','stocks','stockways','productTags','attributes','dangerousGoods')
                                    ->whereHas('stocks',function($q) use ($price)
                                    {
                                        return $q->whereBetween('price',$price);
                                    })
                                    ->get();
            }
            else
            {
                $all_product=Product::with('images','category','features','brand','colors','sizes','stocks','stockways','productTags','attributes','dangerousGoods')
                                    ->get();
            }
        }
        $tag_product=[];
        foreach($tag_products as $p)
        {
            foreach($all_product as $data)
            {
                if($data->id===$p->id)
                {
                    $tag_product[]=$p;
                }
            }
        }
        switch($sort_by)
        {
            case 'Select option':
                $final_product=$tag_product;
                break;
            case 'ASC':
                
                $final_product=collect($tag_product)->sortBy('name',SORT_NATURAL|SORT_FLAG_CASE);
                break;
            case 'DESC':
                $final_product=collect($tag_product)->sortByDesc('name',SORT_NATURAL|SORT_FLAG_CASE);
                break;
            case 'increasing':
                foreach($tag_product as $p)
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
                $final_product=collect($tag_product)->sortBy('price',SORT_NATURAL|SORT_FLAG_CASE);
                break;
            case 'decreasing':
                foreach($tag_product as $p)
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
                $final_product=collect($tag_product)->sortByDesc('price',SORT_NATURAL|SORT_FLAG_CASE);
                break;
            case 'recent';
                $final_product=collect($tag_product)->sortBy('created_at',SORT_NATURAL|SORT_FLAG_CASE);
                break;
            default:
                $final_product=collect($tag_product)->sortByDesc('created_at',SORT_NATURAL|SORT_FLAG_CASE);
                break;
        }
        
        if($paginate==='all')
        {
            $show_products=$final_product;
        }
        else
        {
            $show_products=collect($final_product)->take($paginate);
        }
        
        return view($this->folderName . 'category.color')
        ->with('products',$show_products);
        
    }
    public function catMultipleFilter(Request $request)
    {
        $slug=$request->slug;
        $sort_by=$request->sort_by;
        $paginate=$request->paginate;
        $min_price=$request->min_price;
        $max_price=$request->max_price;
        $color_id=$request->color_id;
        $brand_id=$request->brand_id;
        $cat=Category::descendantsAndSelf(Category::whereSlug($slug)->first());
        $product_id_paginate=[];
        $tag_products = [];
            foreach ($cat as $item) {
                foreach ($item->products as $pro) {
                   
                    array_push($tag_products, $pro);
                }
            }
        
        // $tag_products=$tag->products;
        if($min_price==null && $max_price !=null)
        {
            $min_price=1;
            $max_price=$max_price;
        }
        elseif($min_price ==null && $max_price ==null || $min_price==0 && $max_price==0)
        {
            $min_price=null;
            $max_price=null;
        }
        else
        {
            $min_price=$min_price;
            $max_price=$max_price;
        }
        $price=array($min_price,$max_price);
        if($color_id !=null && $brand_id !=null)
        {
           if($max_price !=null)
           {
               
                $all_product=Product::with('images','category','features','brand','colors','sizes','stocks','stockways','productTags','attributes','dangerousGoods')
                                    ->whereIn('brand_id',$brand_id)
                                    ->whereHas('stocks',function($q) use ($color_id){
                                        return $q->whereIn('color_id',$color_id);
                                    })
                                    ->whereHas('stocks',function($q) use ($price){
                                        return $q->whereBetween('price',$price);
                                    })->get();
           }
           else
           {
                $all_product=Product::with('images','category','features','brand','colors','sizes','stocks','stockways','productTags','attributes','dangerousGoods')
                                    ->whereIn('brand_id',$brand_id)
                                    ->whereHas('stocks',function($q) use ($color_id){
                                        return $q->whereIn('color_id',$color_id);
                                    })->get();
           }

        }
        elseif($color_id==null && $brand_id !=null)
        {
            if($max_price !=null)
            {
                $all_product=Product::with('images','category','features','brand','colors','sizes','stocks','stockways','productTags','attributes','dangerousGoods')
                                    ->whereIn('brand_id',$brand_id)
                                    ->whereHas('stocks',function($q) use ($price){
                                        return $q->whereBetween('price',$price);
                                    })
                                    ->get();
            }
            else
            {
               $all_product=Product::with('images','category','features','brand','colors','sizes','stocks','stockways','productTags','attributes','dangerousGoods')
                                    ->whereIn('brand_id',$brand_id)
                                    ->get();
            }

        }
        elseif($color_id !=null && $brand_id==null)
        {
            if($max_price !=null)
            {
                $all_product=Product::with('images','category','features','brand','colors','sizes','stocks','stockways','productTags','attributes','dangerousGoods')
                                    ->whereHas('stocks',function($q) use ($color_id)
                                    {
                                        return $q->whereIn('color_id',$color_id);
                                    })
                                    ->whereHas('stocks',function($q) use ($price){
                                        return $q->whereBetween('price',$price);
                                    })
                                    ->get();
            }
            else
            {
               $all_product=Product::with('images','category','features','brand','colors','sizes','stocks','stockways','productTags','attributes','dangerousGoods')
                                    ->whereHas('stocks',function($q) use ($color_id){
                                        return $q->whereIn('color_id',$color_id);
                                    })
                                    ->get();
            }
        }
        else
        {
            if($max_price !=null)
            {
                $all_product=Product::with('images','category','features','brand','colors','sizes','stocks','stockways','productTags','attributes','dangerousGoods')
                                    ->whereHas('stocks',function($q) use ($price)
                                    {
                                        return $q->whereBetween('price',$price);
                                    })
                                    ->get();
            }
            else
            {
                $all_product=Product::with('images','category','features','brand','colors','sizes','stocks','stockways','productTags','attributes','dangerousGoods')
                                    ->get();
            }
        }
        $tag_product=[];
        foreach($tag_products as $p)
        {
            foreach($all_product as $data)
            {
                if($data->id===$p->id)
                {
                    $tag_product[]=$p;
                }
            }
        }
        switch($sort_by)
        {
            case 'Select option':
                $final_product=$tag_product;
                break;
            case 'ASC':
                $final_product=collect($tag_product)->sortBy('name',SORT_NATURAL|SORT_FLAG_CASE);
                break;
            case 'DESC':
                $final_product=collect($tag_product)->sortByDesc('name',SORT_NATURAL|SORT_FLAG_CASE);
                break;
            case 'increasing':
                foreach($tag_product as $p)
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
                $final_product=collect($tag_product)->sortBy('price',SORT_NATURAL|SORT_FLAG_CASE);
                break;
            case 'decreasing':
                foreach($tag_product as $p)
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
                $final_product=collect($tag_product)->sortByDesc('price',SORT_NATURAL|SORT_FLAG_CASE);
                break;
            case 'recent';
                $final_product=collect($tag_product)->sortBy('created_at',SORT_NATURAL|SORT_FLAG_CASE);
                break;
            default:
                $final_product=collect($tag_product)->sortByDesc('created_at',SORT_NATURAL|SORT_FLAG_CASE);
                break;
        }
        if($paginate==='all')
        {
            $show_products=$final_product;
        }
        else
        {
            $show_products=collect($final_product)->take($paginate);
        }
        
        $url=route('cat-multiple-filter-data',$request->all());
        $loginCustomer=auth()->guard('customer')->user();
        $searchItemValue='3';
        if($loginCustomer)
        {
            $searchItemValue = $loginCustomer->wholeseller ? '2' : '1';
            $show_products=collect($show_products)->map(function($item) use ($searchItemValue){
                
                if($item->product_for !='3')
                {
                    if($item->product_for ==$searchItemValue)
                    {
                        return $item;
                    }
                }
                else
                {
                    return $item;
                }
            })->whereNotNull();
            
        }
       
        $products = PaginationHelper::paginate(collect($show_products), 40)->withPath($url);

        return view($this->folderName . 'category.color')
        ->with('products',$products);
    }

    public function tagMultipleFilter(Request $request)
    {
        $slug=$request->slug;
        $sort_by=$request->sort_by;
        $paginate=$request->paginate;
        $min_price=$request->min_price;
        $max_price=$request->max_price;
        $color_id=$request->color_id;
        $brand_id=$request->brand_id;
        $tag=Tag::whereSlug($slug)->first();
        $tag_products=$tag->products;
        if($min_price==null && $max_price !=null)
        {
            $min_price=1;
            $max_price=$max_price;
        }
        elseif($min_price ==null && $max_price ==null || $min_price==0 && $max_price==0)
        {
            $min_price=null;
            $max_price=null;
        }
        else
        {
            $min_price=$min_price;
            $max_price=$max_price;
        }
        $price=array($min_price,$max_price);
        if($color_id !=null && $brand_id !=null)
        {
           if($max_price !=null)
           {
               
                $all_product=Product::with('images','category','features','brand','colors','sizes','stocks','stockways','productTags','attributes','dangerousGoods')
                                    ->whereIn('brand_id',$brand_id)
                                    ->whereHas('stocks',function($q) use ($color_id){
                                        return $q->whereIn('color_id',$color_id);
                                    })
                                    ->whereHas('stocks',function($q) use ($price){
                                        return $q->whereBetween('price',$price);
                                    })->get();
           }
           else
           {
                $all_product=Product::with('images','category','features','brand','colors','sizes','stocks','stockways','productTags','attributes','dangerousGoods')
                                    ->whereIn('brand_id',$brand_id)
                                    ->whereHas('stocks',function($q) use ($color_id){
                                        return $q->whereIn('color_id',$color_id);
                                    })->get();
           }

        }
        elseif($color_id==null && $brand_id !=null)
        {
            if($max_price !=null)
            {
                $all_product=Product::with('images','category','features','brand','colors','sizes','stocks','stockways','productTags','attributes','dangerousGoods')
                                    ->whereIn('brand_id',$brand_id)
                                    ->whereHas('stocks',function($q) use ($price){
                                        return $q->whereBetween('price',$price);
                                    })
                                    ->get();
            }
            else
            {
               $all_product=Product::with('images','category','features','brand','colors','sizes','stocks','stockways','productTags','attributes','dangerousGoods')
                                    ->whereIn('brand_id',$brand_id)
                                    ->get();
            }

        }
        elseif($color_id !=null && $brand_id==null)
        {
            if($max_price !=null)
            {
                $all_product=Product::with('images','category','features','brand','colors','sizes','stocks','stockways','productTags','attributes','dangerousGoods')
                                    ->whereHas('stocks',function($q) use ($color_id)
                                    {
                                        return $q->whereIn('color_id',$color_id);
                                    })
                                    ->whereHas('stocks',function($q) use ($price){
                                        return $q->whereBetween('price',$price);
                                    })
                                    ->get();
            }
            else
            {
               $all_product=Product::with('images','category','features','brand','colors','sizes','stocks','stockways','productTags','attributes','dangerousGoods')
                                    ->whereHas('stocks',function($q) use ($color_id){
                                        return $q->whereIn('color_id',$color_id);
                                    })
                                    ->get();
            }
        }
        else
        {
            if($max_price !=null)
            {
                $all_product=Product::with('images','category','features','brand','colors','sizes','stocks','stockways','productTags','attributes','dangerousGoods')
                                    ->whereHas('stocks',function($q) use ($price)
                                    {
                                        return $q->whereBetween('price',$price);
                                    })
                                    ->get();
            }
            else
            {
                $all_product=Product::with('images','category','features','brand','colors','sizes','stocks','stockways','productTags','attributes','dangerousGoods')
                                    ->get();
            }
        }
        $tag_product=[];
        foreach($tag_products as $p)
        {
            foreach($all_product as $data)
            {
                if($data->id===$p->id)
                {
                    $tag_product[]=$p;
                }
            }
        }
        switch($sort_by)
        {
            case 'Select option':
                $final_product=$tag_product;
                break;
            case 'ASC':
                
                $final_product=collect($tag_product)->sortBy('name',SORT_NATURAL|SORT_FLAG_CASE);
                break;
            case 'DESC':
                $final_product=collect($tag_product)->sortByDesc('name',SORT_NATURAL|SORT_FLAG_CASE);
                break;
            case 'increasing':
                foreach($tag_product as $p)
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
                $final_product=collect($tag_product)->sortBy('price',SORT_NATURAL|SORT_FLAG_CASE);
                break;
            case 'decreasing':
                foreach($tag_product as $p)
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
                $final_product=collect($tag_product)->sortByDesc('price',SORT_NATURAL|SORT_FLAG_CASE);
                break;
            case 'recent';
                $final_product=collect($tag_product)->sortBy('created_at',SORT_NATURAL|SORT_FLAG_CASE);
                break;
            default:
                $final_product=collect($tag_product)->sortByDesc('created_at',SORT_NATURAL|SORT_FLAG_CASE);
                break;
        }
        
        if($paginate==='all')
        {
            $show_products=$final_product;
        }
        else
        {
            $show_products=collect($final_product)->take($paginate);
        }

        $url=route('tag-multiple-filter',$request->all());
        $products = PaginationHelper::paginate(collect($show_products), 40)->withPath($url);
        
        return view($this->folderName . 'category.color')
        ->with('products',$products);
    }
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
        if($slug == 'new'){
            $result = Product::with('images','category','features','brand','colors','sizes','stocks','stockways','productTags','attributes','dangerousGoods')->orderBy('created_at','DESC')->get();
            $category_products = [];
            foreach($result as $product)
            {
                array_push($category_products,$product->id);
            }
        }elseif($slug == 'discounted'){
            $all_products = Product::with('images','category','features','brand','colors','sizes','stocks','stockways','productTags','attributes','dangerousGoods')->get();
            $category_products = [];            
            foreach($all_products as $key=>$product){     
                $max_discount = 0;           
                foreach($product->stocks as $pro){
                    if($pro->special_price != null){
                        $discount = $pro->price - $pro->special_price;
                        $per = ($discount/$pro->price)*100;
                        $per = round($per,2);
                        if($per > $max_discount){
                            $max_discount = $per;
                        }          
                    }
                }
                if($max_discount > 0){
                    $product->setAttribute('discount', $max_discount);
                    array_push($category_products, $product->id);
                } 
            }
        }elseif($slug == 'popular'){
            $top_sells = Product::with('images','category','features','brand','colors','sizes','stocks','stockways','productTags','attributes','dangerousGoods')->orderBy('total_sell','DESC')->take(2)->get();
            $category_products = []; 
            foreach($top_sells as $product)
            {
                array_push($category_products,$product->id);
            }
        }else{
            $result = Category::descendantsAndSelf(Category::where('slug',$slug)->first());
            $category_products = [];
            foreach($result as $product)
            {
                foreach($product->products as $pro)
                {
                    array_push($category_products,$pro->id);
                }
            }
        }



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
                
                foreach($colorWithBrand_products as $product){
                    if(in_array($product->id, $category_products)){
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
                
                foreach($colorWithBrand_products as $product){
                    if(in_array($product->id, $category_products)){
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
                
                foreach($colorPrice_products as $product){
                    if(in_array($product->id, $category_products)){
                        array_push($products,$product);
                    }
                }
            }
            else{
                $color_products = Product::with('images','category','features','brand','colors','sizes','stocks','stockways','productTags','attributes','dangerousGoods')->whereHas('images',function($q) use($color_id){
                                    $q->whereIn('color_id', $color_id);
                                    })
                                  ->get();
                
                foreach($color_products as $product){
                    if(in_array($product->id, $category_products)){
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
                
                foreach($brand_products as $product){
                    if(in_array($product->id, $category_products)){
                        array_push($products,$product);
                    }

                }
            }
            else{
                $brand_products = Product::whereIn('brand_id',$brand_id)->get();
                
                foreach($brand_products as $product){
                    if(in_array($product->id, $category_products)){
                        array_push($products,$product);
                    }

                }
            }

        }

        else{
            if( $max_price != null){
                
                $price_products = Product::with('images','category','features','brand','colors','sizes','stocks','stockways','productTags','attributes','dangerousGoods')->whereHas('stocks',function($q) use($price){
                    $q->whereBetween('price', $price);
                })->get();

                foreach($price_products as $product){
                    if(in_array($product->id, $category_products)){
                        array_push($products,$product);
                    }
                }

            }
            else{
                $category_products = [];
                if($slug == 'new'){
                    foreach($result as $product)
                    {
                        array_push($category_products,$product);
                    }
                }elseif($slug == 'discounted'){
                    $all_products = Product::with('images','category','features','brand','colors','sizes','stocks','stockways','productTags','attributes','dangerousGoods')->get();
                    foreach($all_products as $key=>$product){     
                        $max_discount = 0;           
                        foreach($product->stocks as $pro){
                            if($pro->special_price != null){
                                $discount = $pro->price - $pro->special_price;
                                $per = ($discount/$pro->price)*100;
                                $per = round($per,2);
                                if($per > $max_discount){
                                    $max_discount = $per;
                                }          
                            }
                        }
                        if($max_discount > 0){
                            $product->setAttribute('discount', $max_discount);
                            array_push($category_products, $product);
                        } 
                    }
                }elseif($slug == 'popular'){
                    $top_sells = Product::with('images','category','features','brand','colors','sizes','stocks','stockways','productTags','attributes','dangerousGoods')->orderBy('total_sell','DESC')->take(2)->get();
                    foreach($top_sells as $product)
                    {
                        array_push($category_products,$product);
                    }
                }else{
                    foreach($result as $product)
                    {
                        foreach($product->products as $pro)
                        {
                            array_push($category_products,$pro);
                        }
                    }
                }
                $products = $category_products;
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

    public function paginate($items, $perPage = 20, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options = [
            'path' => Paginator::resolveCurrentPath()
        ]);
    }

    public function categoryWiseProduct(Request $request)
    {
        $category=Category::descendantsAndSelf(Category::where('slug', $request->slug)->first());
        // $category=Category::where('slug',$request->slug)->first();
        $product=[];
        foreach($category as $cat)
        {
            foreach($cat->products as $p)
            {
                $product[]=$p;
            }
        }
        // $product=$category->getProduct;
        $products=null;

        
        switch($request->sort_by)
        {
            case 'ASC':
            $products=collect($product);
            $products=collect($product)->sortBy('name',SORT_NATURAL|SORT_FLAG_CASE);
            break;

            case 'DESC':
            $products=collect($product);
            $products=collect($product)->sortByDesc('name',SORT_NATURAL|SORT_FLAG_CASE);
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
            $products=collect($product)->sortBy('price',SORT_NATURAL|SORT_FLAG_CASE);
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
            $products=collect($product)->sortByDesc('price',SORT_NATURAL|SORT_FLAG_CASE);
            break;

            case 'recent':
            $products=collect($product);
            $products=collect($product)->sortBy('created_at',SORT_NATURAL|SORT_FLAG_CASE);
            break;

            case 'old':
            $products=collect($product);
            $products=collect($product)->sortByDesc('created_at',SORT_NATURAL|SORT_FLAG_CASE);
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

    public function categoryWiseProductPaginate(Request $request)
    {
        $category=Category::descendantsAndSelf(Category::where('slug', $request->slug)->first());
        $product=[];
        foreach($category as $cat)
        {
            foreach($cat->products as $p)
            {
                $product[]=$p;
            }
        }
        $products=null;
        switch($request->sort)
        {
            case 'Select option':
            $products=collect($product);
            $products=$product;
            break;

            case 'ASC':
            $products=collect($product);
            $products=collect($product)->sortBy('name',SORT_NATURAL|SORT_FLAG_CASE);
            break;

            case 'DESC':
            $products=collect($product);
            $products=collect($product)->sortByDesc('name',SORT_NATURAL|SORT_FLAG_CASE);
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
            $products=collect($product)->sortBy('price',SORT_NATURAL|SORT_FLAG_CASE);
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
            $products=collect($product)->sortByDesc('price',SORT_NATURAL|SORT_FLAG_CASE);
            break;

            case 'recent':
            $products=collect($product);
            $products=collect($product)->sortBy('created_at',SORT_NATURAL|SORT_FLAG_CASE);
            break;

            case 'old':
            $products=collect($product);
            $products=collect($product)->sortByDesc('created_at',SORT_NATURAL|SORT_FLAG_CASE);
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

    public function searchWithMultipleFilter(Request $request)
    {
        $slug=$request->slug;
        $sort_by=$request->sort_by;
        $paginate=$request->paginate;
        $min_price=$request->min_price;
        $max_price=$request->max_price;
        $color_id=$request->color_id;
        $brand_id=$request->brand_id;
        $tag_products = Product::where('name','like','%'.$slug.'%')->where('publishStatus', ProductStatusEnum::ACTIVE)->where('status', ProductStatusEnum::ACTIVE)->get();
        if($min_price==null && $max_price !=null)
        {
            $min_price=1;
            $max_price=$max_price;
        }
        elseif($min_price ==null && $max_price ==null || $min_price==0 && $max_price==0)
        {
            $min_price=null;
            $max_price=null;
        }
        else
        {
            $min_price=$min_price;
            $max_price=$max_price;
        }
        $price=array($min_price,$max_price);
        if($color_id !=null && $brand_id !=null)
        {
           if($max_price !=null)
           {
               
                $all_product=Product::with('images','category','features','brand','colors','sizes','stocks','stockways','productTags','attributes','dangerousGoods')
                                    ->whereIn('brand_id',$brand_id)
                                    ->whereHas('stocks',function($q) use ($color_id){
                                        return $q->whereIn('color_id',$color_id);
                                    })
                                    ->whereHas('stocks',function($q) use ($price){
                                        return $q->whereBetween('price',$price);
                                    })->get();
           }
           else
           {
                $all_product=Product::with('images','category','features','brand','colors','sizes','stocks','stockways','productTags','attributes','dangerousGoods')
                                    ->whereIn('brand_id',$brand_id)
                                    ->whereHas('stocks',function($q) use ($color_id){
                                        return $q->whereIn('color_id',$color_id);
                                    })->get();
           }

        }
        elseif($color_id==null && $brand_id !=null)
        {
            if($max_price !=null)
            {
                $all_product=Product::with('images','category','features','brand','colors','sizes','stocks','stockways','productTags','attributes','dangerousGoods')
                                    ->whereIn('brand_id',$brand_id)
                                    ->whereHas('stocks',function($q) use ($price){
                                        return $q->whereBetween('price',$price);
                                    })
                                    ->get();
            }
            else
            {
               $all_product=Product::with('images','category','features','brand','colors','sizes','stocks','stockways','productTags','attributes','dangerousGoods')
                                    ->whereIn('brand_id',$brand_id)
                                    ->get();
            }

        }
        elseif($color_id !=null && $brand_id==null)
        {
            if($max_price !=null)
            {
                $all_product=Product::with('images','category','features','brand','colors','sizes','stocks','stockways','productTags','attributes','dangerousGoods')
                                    ->whereHas('stocks',function($q) use ($color_id)
                                    {
                                        return $q->whereIn('color_id',$color_id);
                                    })
                                    ->whereHas('stocks',function($q) use ($price){
                                        return $q->whereBetween('price',$price);
                                    })
                                    ->get();
            }
            else
            {
               $all_product=Product::with('images','category','features','brand','colors','sizes','stocks','stockways','productTags','attributes','dangerousGoods')
                                    ->whereHas('stocks',function($q) use ($color_id){
                                        return $q->whereIn('color_id',$color_id);
                                    })
                                    ->get();
            }
        }
        else
        {
            if($max_price !=null)
            {
                $all_product=Product::with('images','category','features','brand','colors','sizes','stocks','stockways','productTags','attributes','dangerousGoods')
                                    ->whereHas('stocks',function($q) use ($price)
                                    {
                                        return $q->whereBetween('price',$price);
                                    })
                                    ->get();
            }
            else
            {
                $all_product=Product::with('images','category','features','brand','colors','sizes','stocks','stockways','productTags','attributes','dangerousGoods')
                                    ->get();
            }
        }
        $tag_product=[];
        foreach($tag_products as $p)
        {
            foreach($all_product as $data)
            {
                if($data->id===$p->id)
                {
                    $tag_product[]=$p;
                }
            }
        }
        switch($sort_by)
        {
            case 'Select option':
                $final_product=$tag_product;
                break;
            case 'ASC':
                
                $final_product=collect($tag_product)->sortBy('name',SORT_NATURAL|SORT_FLAG_CASE);
                break;
            case 'DESC':
                $final_product=collect($tag_product)->sortByDesc('name',SORT_NATURAL|SORT_FLAG_CASE);
                break;
            case 'increasing':
                foreach($tag_product as $p)
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
                $final_product=collect($tag_product)->sortBy('price',SORT_NATURAL|SORT_FLAG_CASE);
                break;
            case 'decreasing':
                foreach($tag_product as $p)
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
                $final_product=collect($tag_product)->sortByDesc('price',SORT_NATURAL|SORT_FLAG_CASE);
                break;
            case 'recent';
                $final_product=collect($tag_product)->sortBy('created_at',SORT_NATURAL|SORT_FLAG_CASE);
                break;
            default:
                $final_product=collect($tag_product)->sortByDesc('created_at',SORT_NATURAL|SORT_FLAG_CASE);
                break;
        }
        if($paginate==='all')
        {
            $show_products=$final_product;
        }
        else
        {
            $show_products=collect($final_product)->take($paginate);
        }
        $url=route('search.multiple-filter-data',$request->all());
        $products=$products = PaginationHelper::paginate(collect($show_products), 40)->withPath($url);
        
        return view($this->folderName . 'search.filter')
        ->with('products',$products)
        ->with('slug',$slug);
    }

    public function brandMultipleFilter(Request $request)
    {
        $slug=$request->slug;
        $sort_by=$request->sort_by;
        $paginate=$request->paginate;
        $min_price=$request->min_price;
        $max_price=$request->max_price;
        $color_id=$request->color_id;
        $brand_id=$request->brand_id;
        $brand=Brand::where('slug',$slug)->firstOrFail();
        $tag_products = Product::where('brand_id',$brand->id)->where('publishStatus', ProductStatusEnum::ACTIVE)->where('status', ProductStatusEnum::ACTIVE)->get();
        
        if($min_price==null && $max_price !=null)
        {
            $min_price=1;
            $max_price=$max_price;
        }
        elseif($min_price ==null && $max_price ==null || $min_price==0 && $max_price==0)
        {
            $min_price=null;
            $max_price=null;
        }
        else
        {
            $min_price=$min_price;
            $max_price=$max_price;
        }
        $price=array($min_price,$max_price);
        if($color_id !=null && $brand_id !=null)
        {
           if($max_price !=null)
           {
               
                $all_product=Product::with('images','category','features','brand','colors','sizes','stocks','stockways','productTags','attributes','dangerousGoods')
                                    ->whereIn('brand_id',$brand_id)
                                    ->whereHas('stocks',function($q) use ($color_id){
                                        return $q->whereIn('color_id',$color_id);
                                    })
                                    ->whereHas('stocks',function($q) use ($price){
                                        return $q->whereBetween('price',$price);
                                    })->get();
           }
           else
           {
                $all_product=Product::with('images','category','features','brand','colors','sizes','stocks','stockways','productTags','attributes','dangerousGoods')
                                    ->whereIn('brand_id',$brand_id)
                                    ->whereHas('stocks',function($q) use ($color_id){
                                        return $q->whereIn('color_id',$color_id);
                                    })->get();
           }

        }
        elseif($color_id==null && $brand_id !=null)
        {
            if($max_price !=null)
            {
                $all_product=Product::with('images','category','features','brand','colors','sizes','stocks','stockways','productTags','attributes','dangerousGoods')
                                    ->whereIn('brand_id',$brand_id)
                                    ->whereHas('stocks',function($q) use ($price){
                                        return $q->whereBetween('price',$price);
                                    })
                                    ->get();
            }
            else
            {
               $all_product=Product::with('images','category','features','brand','colors','sizes','stocks','stockways','productTags','attributes','dangerousGoods')
                                    ->whereIn('brand_id',$brand_id)
                                    ->get();
            }

        }
        elseif($color_id !=null && $brand_id==null)
        {
            if($max_price !=null)
            {
                $all_product=Product::with('images','category','features','brand','colors','sizes','stocks','stockways','productTags','attributes','dangerousGoods')
                                    ->whereHas('stocks',function($q) use ($color_id)
                                    {
                                        return $q->whereIn('color_id',$color_id);
                                    })
                                    ->whereHas('stocks',function($q) use ($price){
                                        return $q->whereBetween('price',$price);
                                    })
                                    ->get();
            }
            else
            {
               $all_product=Product::with('images','category','features','brand','colors','sizes','stocks','stockways','productTags','attributes','dangerousGoods')
                                    ->whereHas('stocks',function($q) use ($color_id){
                                        return $q->whereIn('color_id',$color_id);
                                    })
                                    ->get();
            }
        }
        else
        {
            if($max_price !=null)
            {
                $all_product=Product::with('images','category','features','brand','colors','sizes','stocks','stockways','productTags','attributes','dangerousGoods')
                                    ->whereHas('stocks',function($q) use ($price)
                                    {
                                        return $q->whereBetween('price',$price);
                                    })
                                    ->get();
            }
            else
            {
                $all_product=Product::with('images','category','features','brand','colors','sizes','stocks','stockways','productTags','attributes','dangerousGoods')
                                    ->get();
            }
        }
        $tag_product=[];
        foreach($tag_products as $p)
        {
            foreach($all_product as $data)
            {
                if($data->id===$p->id)
                {
                    $tag_product[]=$p;
                }
            }
        }
        switch($sort_by)
        {
            case 'Select option':
                $final_product=$tag_product;
                break;
            case 'ASC':
                
                $final_product=collect($tag_product)->sortBy('name',SORT_NATURAL|SORT_FLAG_CASE);
                break;
            case 'DESC':
                $final_product=collect($tag_product)->sortByDesc('name',SORT_NATURAL|SORT_FLAG_CASE);
                break;
            case 'increasing':
                foreach($tag_product as $p)
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
                $final_product=collect($tag_product)->sortBy('price',SORT_NATURAL|SORT_FLAG_CASE);
                break;
            case 'decreasing':
                foreach($tag_product as $p)
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
                $final_product=collect($tag_product)->sortByDesc('price',SORT_NATURAL|SORT_FLAG_CASE);
                break;
            case 'recent';
                $final_product=collect($tag_product)->sortBy('created_at',SORT_NATURAL|SORT_FLAG_CASE);
                break;
            default:
                $final_product=collect($tag_product)->sortByDesc('created_at',SORT_NATURAL|SORT_FLAG_CASE);
                break;
        }
        if($paginate==='all')
        {
            $show_products=$final_product;
        }
        else
        {
            $show_products=collect($final_product)->take($paginate);
        }
        return view($this->folderName .'brand.filter')
        ->with('products',$show_products)
        ->with('slug',$slug);
    }
}
