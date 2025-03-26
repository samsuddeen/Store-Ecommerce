<?php
namespace App\Data\Product;

use App\Models\Tag;
use App\Models\Review;
use App\Models\Product;
use App\Models\Wishlist as WishList;
use App\Models\QuestionAnswer;
use Illuminate\Support\Facades\Session;

class ProductDetailData
{
    function __construct()
    {
        
    }
    public function getDetails($slug)
    {
        if(Session::has('pro_ids')){
            $pro_ids = Session::get('pro_ids');
        }
        else{
            $pro_ids = [];
        }
        
        $product_id = Product::where('slug',$slug)->value('id');

        array_push($pro_ids, $product_id);
        Session::put('pro_ids', $pro_ids);


        $category_id = Product::where('slug', $slug)->value('category_id');
        $related_products = Product::where('category_id',$category_id)->where('slug','!=',$slug)->inRandomOrder()->take(10)->get();
        $product = Product::with('images','category','features','brand','colors','sizes', 'stockways','productTags','attributes','dangerousGoods')->with(['stocks'=>function($query1){
                $query1->with(['stockWays'=>function($query2){
                        $query2->with('category');
                }]);
        }])->where('slug',$slug)->first();
        $stocks = $product->stocks;
        $new_stock = collect($stocks)->map(function($row){
                    return $row;
        })->toArray();
        $final_stock = collect($new_stock)->map(function($row){
                    $product_find=Product::where('id',$row['product_id'])->first();
                    $offer_price=getDetailOfferProduct($product_find,$row);
                    return [
                        'price'=>($row['special_price'] ?? $row['price']),
                        'original_price'=>$row['price'],
                        'offer_price'=>$offer_price,
                        'color'=>$row['color_id'],
                        'attribute'=>collect($row['stock_ways'])->map(function($row1){
                            return[
                                'name'=>$row1['category']['title'] ?? null,
                                'values'=>$row1['value'],

                            ];
                        }),
                    ];
        })->values()->all();
        $tags = Tag::where('publishStatus', true)->orderBy('order', 'asc')->get();
        if(auth()->guard('customer')->user()){
            $iswish=WishList::where('product_id',$product_id)->where('user_id', auth()->guard('customer')->user()->id)->first();
            $comments = QuestionAnswer::where(['product_id'=>$product_id, 'status'=>true])->orWhere('customer_id', auth()->guard('customer')->user()->id)->get();
            // $iswish=WishList::where('product_id',$product_id)->where('user_id',auth()->guard('customer')->user()->id)->first();
        }else{
            $comments = QuestionAnswer::where(['product_id'=>$product_id,'status'=>true])->get();
            $iswish= null;

        }
        $reviews = Review::where('product_id',$product_id)->whereNull('parent_id')->orderBy('id','DESC')->where('status','1')->get();
        $totalRating = Review::where('product_id',$product_id)->count();
        $fiveStarRating = Review::where('product_id',$product_id)->where('rating',5)->count();
        $fourStarRating = Review::where('product_id',$product_id)->where('rating',4)->count();
        $threeStarRating = Review::where('product_id',$product_id)->where('rating',3)->count();
        $twoStarRating = Review::where('product_id',$product_id)->where('rating',2)->count();
        $oneStarRating = Review::where('product_id',$product_id)->where('rating',1)->count();
        $totalStar = Review::where('product_id',$product_id)->sum('rating');
        $da = Product::with('features', 'brand', 'colors', 'sizes', 'stocks', 'stockways', 'productTags', 'attributes', 'dangerousGoods')->where('slug',$slug)->first();
        $datas = collect($da)->map(function($row)use($da){
            foreach($da->stocks as $dat){

                foreach($dat->stockWays as $water){
                    return $row;
                }

            }
        });
        $product_colors = [];
        foreach($product->stocks as $stock){
            foreach($stock->color as $col){
                array_push($product_colors, $col->title);
            }
        }
        $product_colors = array_unique($product_colors) ;
        $attributes_key = [];
        $attributes = [];

        foreach($product->stocks as $stock){
            if(isset($stock->stockWays)){
                foreach($stock->stockWays as $sw){
                    array_push($attributes_key, $sw->categoryAttribute->title);
                    array_push($attributes, [$sw->categoryAttribute->title => $sw->value]);
                }
            }
        }
        $attributes_key = array_unique($attributes_key);
        
        $data = [
            'da'=>$da,
            'final_stock'=>$final_stock,
            'iswish'=>$iswish,
            'comments'=>$comments,
            'totalStar'=>$totalStar,
            'fiveStarRating'=>$fiveStarRating,
            'oneStarRating'=>$oneStarRating,
            'twoStarRating'=>$twoStarRating,
            'threeStarRating'=>$threeStarRating,
            'fourStarRating'=>$fourStarRating,
            'totalRating'=>$totalRating,
            'reviews'=>$reviews,
            'related_products'=>$related_products,
            'product'=>$product,
            'tags'=>$tags,
            'product_colors'=>$product_colors,
            'attributes'=>$attributes,
            'attributes_key'=>$attributes_key,
        ];
        return $data;
    }
}