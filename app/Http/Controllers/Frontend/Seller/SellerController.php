<?php

namespace App\Http\Controllers\Frontend\Seller;

use App\Models\Brand;
use App\Models\Color;
use App\Models\Review;
use App\Models\Seller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Seller\SellerSetting;

class SellerController extends Controller
{
    public function sellerView(Request $request,$slug)
    {
        // dd($request->all(),$slug);
        $seller=Seller::where('slug',$slug)->firstOrFail();
        $category=[];
        $all_product=Product::where('seller_id',$seller->id)->get();
        $product_id=[];
        $all_category=Category::with(['ancestors', 'descendants'])->with('children')->where('status',1)->get();
        $seller_cat_id=[];
        foreach($all_product as $product)
        {
            $product_id[]=$product->id;
            $seller_cat_id[]=$product->category->id;
            $seller_cat_id[]=$product->category->parent_id;
        }
        $seller_original_cat=collect($seller_cat_id)->unique();
        $seller_category_show=[];
        foreach($seller_original_cat->toArray() as $cat)
        {
            if($cat!=null)
            {
                $cat=Category::where('id',$cat)->first();
            
            if($cat->parent_id==null && empty($cat->parent_id))
            {
                $seller_category_show[]=$cat->id;
            }
            }
        }
        $parent_detail=[];
        $count=1;
        foreach($seller_category_show as $key=>$parent_cat)
        {   
            $category_name=Category::where('id',$parent_cat)->first();
            if($category_name->parent_id==null)
            {
                $parent_cat=$category_name;
                $result=Category::descendantsAndSelf(Category::where('id', $category_name->id)->first());
                $products = [];
                foreach ($result as $product) {
                    foreach ($product->products as $pro) {
                        if(in_array($pro->id,$product_id))
                        {
                            array_push($products, $pro);
                        }
                       
                    }
                }
                $parent_detail[$count]['parent_cat']=$parent_cat;
                $parent_detail[$count]['product']=$products;
                $count++;
            }
            
        }
        if(!empty(session('pro_ids')))
        {
            $recent_view_product_id=session('pro_ids');
            $just_for_you=collect($recent_view_product_id)->unique();
        }
        else
        {
            $just_for_you=null;
        }

        $seller_setting = SellerSetting::where('seller_id', $seller->id)->first();        
        if($just_for_you!=null)
        {
            $just_for_you_product=Product::whereIn('id',$just_for_you)->get();
        }
        else
        {
            $just_for_you_product=null;
        }
       
        $main_category = Category::ancestorsAndSelf(Category::where('slug', $slug)->first());
        $result = Category::descendantsAndSelf(Category::where('slug', $slug)->first());
        session()->put('seller_category',$seller_original_cat->toArray());
        session()->put('seller_parent_category',$parent_detail);
        session()->put('just_for_you_product',$just_for_you_product);     
        return view('frontend.seller.sellerfront.index',compact('seller', 'seller_setting'));
    }
    public function ProductView(Request $request,$slug,$q=null)
    {
        $seller=Seller::where('slug',$slug)->firstOrFail();
        $seller_setting = SellerSetting::where('seller_id', $seller->id)->first(); 
        if($q !=null && !empty($q))
        {
            $result = Category::descendantsAndSelf(Category::where('slug', $q)->first());
            $all_product = [];
            foreach ($result as $product) {
                foreach ($product->products as $pro) {
                    if($pro->seller_id===$seller->id)
                    {
                        array_push($all_product, $pro);
                    }
                }
            }
            $slug=$q;
        }
        else
        {
            $all_product=Product::where('seller_id',$seller->id)
            ->with('images', 'category', 'features', 'brand', 'colors', 'sizes', 'stocks', 'stockways', 'productTags', 'attributes', 'dangerousGoods')
            ->get();
            $slug='all';
        }
        $selected_cat = Category::get();
        $main_category = Category::get();
        $colors = Color::get();
        $brands = Brand::get();
        $products=Product::get();
        return view('frontend.seller.sellerfront.products',compact('all_product', 'colors', 'brands',  'main_category', 'selected_cat','seller','slug','seller_setting'));
    }

    public function ProfileView(Request $request,$slug)
    {
        $seller=Seller::where('slug',$slug)->firstOrFail();
        $seller_setting = SellerSetting::where('seller_id', $seller->id)->first(); 
        $seller_review=Review::where('seller_id',$seller->id)->whereNull('parent_id')->latest()->get();
        $data=$this->getSellerRating($seller);
        if ($data['totalRating']== 0) {
            $data['totalRating']= 1;
        }
        return view('frontend.seller.sellerfront.profile',compact('seller','seller_review','data','seller_setting'));

    }

    public function getSellerRating($seller)
    {

         return $data=[
             'oneStar'=>Review::where('seller_id',$seller->id)->where('rating',1)->count(),
             'twoStar'=>Review::where('seller_id',$seller->id)->where('rating',2)->count(),
             'threeStar'=>Review::where('seller_id',$seller->id)->where('rating',3)->count(),
             'fourStar'=>Review::where('seller_id',$seller->id)->where('rating',4)->count(),
             'fiveStar'=>Review::where('seller_id',$seller->id)->where('rating',5)->count(),
             'totalRating'=>Review::where('seller_id',$seller->id)->count(),
             'totalStar'=>Review::where('seller_id',$seller->id)->sum('rating'),
         ];
    }

    public function sellerMultipleFilter(Request $request)
    {
        $seller=Seller::where('id',$request->seller_id)->first();
        if(!$seller)
        {
            $response=[
                'error'=>true,
                'msg'=>'Sorry !! Something Went Wrong !!'
            ];
            return response()->json($response,200);
        }
        
        $seller_product=Product::where('seller_id',$seller->id)
                                ->with('images', 'category', 'features', 'brand', 'colors', 'sizes', 'stocks', 'stockways', 'productTags', 'attributes', 'dangerousGoods')
                                ->get();
        
        $slug=$request->slug;
        $sort_by=$request->sort_by;
        $paginate=$request->paginate;
        $min_price=$request->min_price;
        $max_price=$request->max_price;
        $color_id=$request->color_id;
        $brand_id=$request->brand_id;
        if($slug==='all')
        {
            $tag_products = $seller_product;
               
        }
        else
        {
            $cat=Category::descendantsAndSelf(Category::whereSlug($slug)->first());
            $tag_product = [];
            $tag_products = [];
                foreach ($cat as $item) {
                    foreach ($item->products as $pro) {
                        array_push($tag_product, $pro);
                    }
                }
            foreach($seller_product as $seller_data)
            {
                foreach($tag_product as $tproduct)
                {
                    if($seller_data->id===$tproduct->id)
                    {
                        $tag_products[]=$tproduct;
                    }
                }
            }
        }

        
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

        
        
        
        return view('frontend.seller.sellerfront.sellerfilterpage')
        ->with('all_product',$show_products);
    }

    public function productFilter(request $request)
    {
        $page_value=$request->page_value;
        $star_value=$request->star_value;
        $seller_id=$request->seller_id;

        $seller=Seller::where('id',$seller_id)->first();
        if(!$seller)
        {
            $response=[
                'error'=>true,
                'msg'=>'Something Went Wrong'
            ];
            return response()->json($response,200);
        }

        $seller_review=Review::where('seller_id',$seller->id)->whereNull('parent_id')->get();
        $seller_product_id=[];
        foreach($seller_review as $data)
        {
            $seller_product_id[]=$data->product_id;
        }
        $seller_product_final_id=collect($seller_product_id)->unique();
        $products=Product::whereIn('id',$seller_product_final_id->toArray())->where('seller_id',$seller->id)->get();
        $final_data=null;

        switch($page_value)
        {
            case 'recent':
                $final_data=Review::where('seller_id',$seller->id)->whereNull('parent_id')->latest()->get();
                break;
            case 'increasing':
                foreach($seller_review as $p)
                {
                    
                    $offer_price=getOfferProduct($p->product,$p->product->stocks[0]);
                    
                    if($offer_price !=null)
                    {
                        $price=$offer_price;
                    }
                    elseif($p->product->getPrice->special_price !=null)
                    {
                        $price=$p->product->getPrice->special_price;
                    }
                    else
                    {
                        $price=$p->product->getPrice->price;
                    }
                    $p->setAttribute('price',$price);
                    
                }
                $final_data=collect($seller_review)->sortBy('price',SORT_NATURAL|SORT_FLAG_CASE);
                break;
            case 'decreasing':
                foreach($seller_review as $p)
                {
                    
                    $offer_price=getOfferProduct($p->product,$p->product->stocks[0]);
                    
                    if($offer_price !=null)
                    {
                        $price=$offer_price;
                    }
                    elseif($p->product->getPrice->special_price !=null)
                    {
                        $price=$p->product->getPrice->special_price;
                    }
                    else
                    {
                        $price=$p->product->getPrice->price;
                    }
                    $p->setAttribute('price',$price);
                    
                }
                $final_data=collect($seller_review)->sortByDesc('price',SORT_NATURAL|SORT_FLAG_CASE);
                break;
        }

        if($star_value!='all')
        {
            $final_data=$final_data->where('rating',$star_value);
        }

        
        return view('frontend.seller.sellerfront.sellerfilter')
        ->with('seller_review',$final_data);
        
        
    }

    public function searchItem(Request $request)
    {
        $seller=Seller::where('id',$request->seller_id)->first();
        if(!$seller)
        {
            $request->session()->flash('error','Sorry !! Something Went Wrong');
            return redirect()->back();
        }
        $seller_review=Review::where('seller_id',$seller->id)->whereNull('parent_id')->get();
        $all_product=Product::where('name','like','%'.$request->search_field.'%')->where('seller_id',$seller->id)->get();
        $colors = Color::get();
        $brands = Brand::get();
        $products=Product::get();
        $search_value=$request->search_field;
        return view('frontend.seller.sellerfront.search',compact('all_product', 'colors', 'brands','seller','search_value'));
    }

    public function sellerSearchMultipleFilter(request $request)
    {
        $seller=Seller::where('id',$request->seller_id)->first();
        if(!$seller)
        {
            $response=[
                'error'=>true,
                'msg'=>'Sorry !! Something Went Wrong !!'
            ];
            return response()->json($response,200);
        }
        
        
        $seller_product=Product::where('name','like','%'.$request->search_field.'%')->where('seller_id',$seller->id)
                                ->with('images', 'category', 'features', 'brand', 'colors', 'sizes', 'stocks', 'stockways', 'productTags', 'attributes', 'dangerousGoods')
                                ->get();
        $slug=$request->slug;
        $sort_by=$request->sort_by;
        $paginate=$request->paginate;
        $min_price=$request->min_price;
        $max_price=$request->max_price;
        $color_id=$request->color_id;
        $brand_id=$request->brand_id;
       
        $tag_products = $seller_product;           
       
        
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

        // dd($show_products);

        

        return view('frontend.seller.sellerfront.sellersearchfilterpage')
        ->with('all_product',$show_products);
    }

}

