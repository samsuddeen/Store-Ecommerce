<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\{
    Product,
    User,
    Cart,
    Wishlist,
    Category,
    New_Customer,
    Color,
    CategoryAttribute,
    ProductAttribute,
    Seller
};
use App\Models\Admin\VatTax;
use App\Models\ProductStock;
use App\Models\Admin\Product\Featured\FeaturedSection;
use Illuminate\Support\Arr;
use App\Http\Controllers\ProductAttributeDetailController;
use App\Models\StockWays;

class ProductController extends Controller
{
    protected $product = null;
    protected $user = null;
    protected $cart = null;
    protected $wishlist = null;
    protected $featuredsection = null;

    public function __construct(Product $product, User $user, Cart $cart, Wishlist $wishList, FeaturedSection $featuredsection)
    {
        $this->product = $product;
        $this->user = $user;
        $this->cart = $cart;
        $this->wishlist = $wishList;
        $this->featuredsection = $featuredsection;
    }

    public function products(Request $request)
    {
        $filters = $request->all();
        if (!Arr::get($filters, 'per_page')) {
            $filters['per_page'] = 2;
        }
       
        
        try{
            $userType = $request->user_type ?? null;

            $productsQuery = Product::with('brand', 'images', 'getPrice', 'iswish')->latest();
    
            if ($userType && $userType != null) {
                if ($userType == '2') {
                    $productsQuery = $productsQuery->where('product_for', '!=', '2');
                } else {
                    $productsQuery = $productsQuery->where('product_for', '!=', '1');
                }
            }
    
            $products = $productsQuery->get();
    
            if ($userType && $userType != null && $userType != '2') {
                $products = $products->map(function ($item) {
                    $item->getPrice->price = $item->getPrice->wholesaleprice;
                    return $item;
                });
            }
    
            $products = new \Illuminate\Pagination\LengthAwarePaginator(
                $products->forPage($filters['page'] ?? 1, $filters['per_page']),
                $products->count(),
                $filters['per_page'],
                $filters['page'] ?? 1,
                ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
            );
            foreach ($products as $data) {
                $offer=getOfferProduct($data,$data->stocks[0]);
                if($offer !=null)
                {
                    $data['getPrice']->special_price=$offer;
                }
                $data->setAttribute('varient_id', $data->stocks[0]->id);
                $data->setAttribute('percent',  apigetDiscountPercnet($data->id) ?? null);
               
    
            }
            return response()->json($products, 200);
        }catch(\Exception $ex){
            $response = [
                'error' => true,
                'data' => null,
                'msg' => $ex->getMessage()
            ];
            return response()->json($response, 200);
        }
    }

    public function featuredProduct(Request $request)
    {

        try{
            $this->featuredsection = $this->featuredsection->with('product', 'product.images', 'product.getPrice')->get();
            foreach ($this->featuredsection as $key => $data) {
                foreach ($data->product as $product) {
                    $product->setAttribute('varient_id', $product->stocks[0]->id);
                    if(getOfferProduct($product,$product->stocks[0]) !=null)
                    {
                        $product['getPrice']['special_price']=getOfferProduct($product,$product->stocks[0]);
                    }
                }
                $data->product->makeHidden([
                    'pivot','stocks','getOfferProduct'
                ]);
            }
    
            $response = [
                'error' => false,
                'data' => $this->featuredsection,
                'msg' => 'Featured With Products'
            ];
            return response()->json($response, 200);
        }catch(\Exception $ex){
            $response = [
                'error' => true,
                'data' => null,
                'msg' => $ex->getMessage()
            ];
            return response()->json($response, 200);
        }
    }

    public function getSpecialFeaturedProduct(Request $request)
    {
        $featured = FeaturedSection::where('id', $request->id)->with('product', 'product.images', 'product.getPrice')->first();
        if(!$featured)
        {
            $response=[
                'error'=>false,
                'data'=>null,
                'msg'=>'Not Items Found !!'
            ];
            return response()->json($response,200);
        }
        try{
            foreach ($featured->product as $product) {
                if(getOfferProduct($product,$product->stocks[0]) !=null)
                {
                    $product['getPrice']['special_price']=getOfferProduct($product,$product->stocks[0]);
                }
                $product->setAttribute('varient_id', $product->stocks[0]->id);
                $product->makeHidden([
                    'pivot','stocks','getOfferProduct'
                ]);
            }
            $response = [
                'error' => false,
                'data' => $featured,
                'msg' => ' Featured Product Of ' . $featured->title
            ];
            return response()->json($response);
        }catch(\Exception $ex){
            $response = [
                'error' => true,
                'data' =>null,
                'msg' => $ex->getMessage()
            ];
            return response()->json($response);
            
        }
    }

    public function search(Request $request)
    {
        $search = Product::where('name', 'LIKE', '%' . $request->searchitem . '%')->with('images', 'getPrice', 'iswish')->get();
        $userType=$request->user_type ?? null;
        if($userType && $userType !=null){
            if($userType=='2'){
                $search=$search->where('product_for','!=','2')->values();
            }else{
                $search = $search->where('product_for', '!=', '1')->map(function($item) {
                    $item->getPrice->price = $item->getPrice->wholesaleprice;
                    return $item; 
                })->values();
            }
        }
        foreach ($search as $data) {

            $data->setAttribute('varient_id', $data->stocks[0]->id);
            $data->setAttribute('percent',  apigetDiscountPercnet($data->id) ?? null);
            $data->makeHidden([
                "total_sell",
                'keyword',
                "country_id",
                "category_id",
                "user_id",
                "publishStatus",
                "created_at",
                "updated_at",
            ]);
            $data->images->makeHidden([
                "created_at",
                "updated_at"
            ]);
        }
        if ($search->count() > 0) {
            $response = [
                'error' => false,
                'data' => $search,
                'msg' => 'Searched Items !!'
            ];
            return response()->json($response, 200);
        } else {
            $response = [
                'error' => true,
                'data' => null,
                'msg' => 'Sorry !! No Data Found'
            ];
            return response()->json($response, 500);
        }
    }

    public function sortProduct(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'cat_id' => 'required',
            'value' => 'required'
        ]);
        if ($validate->fails()) {
            $response = [
                'error' => true,
                'data' => null,
                'msg' => $validate->errors()
            ];
            return response()->json($response, 500);
        }

        try {
            $childrens = Category::descendantsAndSelf(Category::where('id', $request->cat_id)->first());
            $cats = Category::where('id', $request->cat_id)->first();
            $all_products = [];
            $i = 0;
            foreach ($childrens as $key => $cat) {
                if ($cat->products->count() > 0) {
                    foreach ($cat->products as $value => $product) {
                        $all_products[$i]['id'] = $product->id;
                        $all_products[$i]['name'] = $product->name;
                        $all_products[$i]['slug'] = $product->slug;
                        $all_products[$i]['short_description'] = $product->short_description;
                        $all_products[$i]['long_description'] = $product->long_description;
                        $all_products[$i]['rating'] = $product->rating;
                        $all_products[$i]['price'] = $product->getPrice->price;
                        $all_products[$i]['special_price'] = $product->getPrice->special_price;
                        $all_products[$i]['image'] = $product->images[0]['image'];
                        $i++;
                    }
                }
            }
            if ($request->value === 'low') {
                $next = 'High';
                $vouchers = collect($all_products)->sortBy('price')->values()->all();
            } else {
                $next = 'Low';

                $new = collect($all_products);
                $vouchers = collect($all_products)->sortByDesc('price')->values()->all();
            }
            $response = [
                'error' => false,
                'data' => [
                    "id" => $cats->id,
                    "title" => $cats->title,
                    "slug" => $cats->slug,
                    "image" => $cats->image,
                    "showOnHome" => $cats->showOnHome,
                    "parent_id" => $cats->parent_id,
                    'products' => $vouchers
                ],
                'msg' => 'Sort Product By ' . ucfirst($request->value) . ' - ' . ucfirst($next)
            ];

            return response()->json($response, 200);
        } catch (\Exception $ex) {
            $response = [
                'error' => true,
                'data' => null,
                'msg' => $ex->getMessage()
            ];

            return response()->json($response, 200);
        }
    }

    public function productDetails(Request $request)
    {
        $arr = [];
        $color_collection = [];
        $i = 0;
        try {
            $product = Product::where('slug', $request->slug)->select(['id', 'vat_percent','name', 'slug', 'short_description', 'long_description', 'brand_id', 'category_id', 'rating','seller_id'])->get();
            if ($product->count() <= 0) {
                $response = [
                    'error' => true,
                    'data' => null,
                    'msg' => 'Product Not Found !!'
                ];
                return response()->json($response);
            }
            $vatTax=VatTax::first() ?? 0;
            foreach ($product as $data) {
                $arr['id'] = $data->id;
                $arr['name'] = $data->name;
                $arr['slug'] = $data->slug;
                $arr['short_description'] = ($data->short_description);
                $arr['long_description'] = ($data->long_description);
                $arr['brand_id'] = $data->brand_id;
                $arr['category_id'] = $data->category_id;
                $arr['rating'] = $data->rating;
                $arr['is_wish'] = false;
                if($data->vat_percent){
                    $arr['vat']=($data->vat_percent==1) ? 'Vat Included' : '+'.(int)$vatTax->vat_percent.' %';
                }
                $arr['seller_name']=seller($data->seller_id ?? 6)  ;
                if ($request->user_id) {
                    $user = New_Customer::where('id', $request->user_id)->first();
                    $wish = WishList::where('product_id', $data->id)->where('user_id', $user->id)->first();
                    if ($wish) {
                        $arr['is_wish'] = true;
                    }
                }
                $image = $data->images;
                $price = $data->getPrice;
                $arr['price'] = (int)($price->price);
                $arr['special_price'] = (int)($price->special_price);
                if(getOfferProduct($data, $data->stocks[0]) !=null)
                {
                    $arr['special_price'] = getOfferProduct($data, $data->stocks[0]);
                } 
                $userData=$request->user_type ?? null;
                $wholeSellerStatus=false;
                if($userData=='1'){
                    $wholeSellerStatus=true;
                }
                if($wholeSellerStatus){
                    $arr['price']=(int)($price->wholesaleprice);
                }
                $arr['special_from'] = $price->special_from;
                $arr['special_to'] = $price->special_to;
                $arr['quantity'] = $price->quantity;
                $category = $data->category;
                $arr['category']['id'] = $category->id;

                $arr['category']['title'] = $category->title;

                $arr['category']['slug'] = $category->slug;

                $arr['category']['image'] = $category->image;

                $arr['category']['showOnHome'] = $category->showOnHome;
                $arr['category']['parent_id'] = $category->parent_id;

                foreach ($image as $key => $img) {
                    $arr['image'][$key]['id'] = $img->id;
                    $arr['image'][$key]['image'] = $img->image;
                    $arr['image'][$key]['is_featured'] = $img->is_featured;
                }
                $attr = $data->attributessss;
                if($attr){
                    $arr['attribute'] = [];
                    foreach ($attr as $attrkey => $at) {
                        $attributeTitle = $at->categoryAttribute->title;
                        $attributeValue = $at->categoryAttribute->value;
                        $arr['attribute'][$attributeTitle] = $attributeValue;
                        // $arr['attribute'][$attrkey] = $at->categoryAttribute->title . '-' .$at->categoryAttribute->value;
                    }
                    $arr['attribute'][] = 'color';
                    // $arr['attribute'][] = 'price';
                    // $arr['attribute'][] = 'special_price';
                    // $arr['attribute'][] = 'quantity';
                    // $arr['attribute'][] = 'free_items';
                }
                foreach ($data->stocks as $stkey => $stock) {
                    if($stock->color_id)
                    {
                        $color_collection[$stkey] = $stock->getColor->id;
                    }
                }

                foreach ($data->stocks as $stkey => $stock) {
                    if($stock->color_id)
                    {
                        $same_color = ProductStock::where('product_id', $product[0]->id)->where('color_id', $stock->color_id)->get();
                    }else{
                        $same_color = ProductStock::where('product_id',$product[0]->id)->get();
                    }
                    if ($same_color->count() > 1) {
                        foreach ($same_color as $color_key => $color) {
                            $arr['selected_data'][$color_key]['id'] = $color->id;
                            foreach ($color->getStock as $st) {

                                $arr['selected_data'][$color_key][$st->getOption->title] = $st->value;
                            }
                            if($color->getColor)
                            {
                                $arr['selected_data'][$color_key]['color'] = $color->getColor->colorCode;
                            }
                            $arr['selected_data'][$color_key]['price'] = (int)($color->price);
                            $arr['selected_data'][$color_key]['special_price'] = (int)($color->special_price);
                            $arr['selected_data'][$color_key]['quantity'] = $color->quantity ?? 0;
                            $arr['selected_data'][$color_key]['free_items'] = $color->free_items;
                            foreach ($color->getStock as $newst) {
                                $arr['selected_data'][$color_key]['items_id'][$newst->getOption->title] = $newst->getOption->id;
                            }
                            $arr['selected_data'][$color_key]['items_id']['color'] = $color->color_id;
                        }
                        break;
                    } else {
                        $arr['selected_data'][$stkey]['id'] = $stock->id;
                        foreach ($stock->getStock as $st) {
                            $arr['selected_data'][$stkey][$st->getOption->title] = $st->value;
                        }
                        if($stock->getColor){
                            $arr['selected_data'][$stkey]['color'] = $stock->getColor->colorCode;
                        }
                        
                        $arr['selected_data'][$stkey]['price'] = (int)($stock->price);
                        $arr['selected_data'][$stkey]['special_price'] = (int)($stock->special_price);
                        $offer=getOfferProduct($product[0], $stock);
                        if($offer !=null)
                        {
                            $arr['selected_data'][$stkey]['special_price'] =$offer;
                        }
                        $arr['selected_data'][$stkey]['quantity'] = $stock->quantity ?? 0;
                        $arr['selected_data'][$stkey]['free_items'] = $stock->free_items;
                        foreach ($stock->getStock as $st) {
                            $arr['selected_data'][$stkey]['items_id'][$st->getOption->title] = $st->getOption->id;
                        }
                        $arr['selected_data'][$stkey]['items_id']['color'] = $stock->color_id;
                        break;
                    }
                }
                $final_color_collection = array_unique($color_collection);
                $color_count = 0;
                foreach ($final_color_collection as $color_code) {
                    $color_detail = Color::where('id', $color_code)->first();
                    $arr['colors'][$color_count]['id'] = $color_detail->id;
                    $arr['colors'][$color_count]['name'] = $color_detail->title;
                    $arr['colors'][$color_count]['code'] = $color_detail->colorCode;
                    $color_count++;
                }
            }

            $nullValue='';
            
            $colorData=$this->getAllProductdetailColor($data)['colors'];
            foreach($colorData as $key=>$colorValue)
            {
                $nullValue.=$colorValue['title'];
                $nullValue.=',';
            }

            $arr['specificationData']=[
                'brand'=>$data->brand->name ?? $data->category->title,
                'color'=>$nullValue ?? '',
                // 'specification'=>getSpecificationHtmlgenerate($data) ?? null
                'specification'=>getSpecificationHtmlgenerateApi($data) ?? null
            ];
            $arr['selected_attributes'] = $data->stockways;
            $stock_price = [];
            $special_price = [];
            foreach($arr['selected_attributes'] as &$attr)
            {
                $stock_price[] = $attr->stock->price;
                $attr->stock_price = $attr->stock->price;
                $attr->special_price = $attr->stock->special_price;
            }

            
            if ($data->seller_id != null) {
                $seller = seller::where('id', $data->seller_id)->first();
            }
            // $arr['sellerUrl']=route('seller', $seller->slug);
            // if($product[0]->vat_percent==0)
            // {
            //     $vatTax=VatTax::first();
            //     $vatPercent=(int)$vatTax->vat_percent;
                
            //     $arr['price']=$arr['price']+round(((int)$arr['price']*$vatPercent)/100);
            //     if($arr['special_price'] !=0 && $arr['special_price'] >0)
            //     {
            //         $arr['special_price']=$arr['special_price']+round(((int)$arr['special_price']*$vatPercent)/100);
            //     }
            // }

            $arr['productNewAttribute']=$this->productNewAttribute($product->first(),$wholeSellerStatus);
            
            $response = [
                'error' => false,
                'data' => $arr,
                'msg' => 'Product Details '
            ];
            return response()->json($response, 200);
        } catch (\Exception $ex) {
            $response = [
                'error' => true,
                'data' => null,
                'msg' => $ex->getMessage()
            ];
            return response()->json($response, 200);
        }
    }
    public function productNewAttribute($product,$wholeSellerStatus){
        $stocks = $product->stocks;
        $finalArrayData = [];
        $colorData = [];
        $stocksKey = [];
        
        foreach ($stocks as $key => $stockData) {
            $color = $stockData->color->first();
            if ($color) {
                $colorId = $color->id;
                $colorTitle = $color->title;
                $colorCode = $color->colorCode;
                $price = $stockData->special_price ? $stockData->special_price : $stockData->price;
                if($wholeSellerStatus){
                    $price = $stockData->wholesaleprice ;
                }
                if (!isset($finalArrayData[$colorCode])) {
                    $finalArrayData[$colorCode] = [
                        'color' => [
                            'id' => $colorId,
                            'title' => $colorTitle,
                            'color_code' => $colorCode,
                        ],
                        'attributes' => []
                    ];
                }
    
                $existingAttributes = [];
                // foreach ($finalArrayData[$colorCode]['attributes'] as $existingAttribute) {
                //     $existingAttributes[$existingAttribute['title']][] = $existingAttribute['value'];
                // }
    
                $totalQuantity = $stockData->quantity;
                foreach ($stockData->stockways as $ways) {
                    $stocksKey[] = [
                        'id' => $ways->categoryAttribute->id,
                        'title' => $ways->categoryAttribute->title
                    ];
    
                    $attributeId = $ways->categoryAttribute->id;
                    $attributeTitle = $ways->categoryAttribute->title;
                    $attributeValue = $ways->value;
                    $stockId = $ways->stock_id;
                    $color_code = $colorCode;
    
                    if (!isset($existingAttributes[$attributeTitle]) || !in_array($attributeValue, $existingAttributes[$attributeTitle])) {
                       
                        $finalArrayData[$colorCode]['attributes'][] = [
                            'id' => $attributeId,
                            'title' => $attributeTitle,
                            'value' => $attributeValue,
                            'stock_id' => $stockId,
                            'color_code' => $color_code,
                            'price' => $price,
                            'totalQty' => $totalQuantity,
                            'is_same' => false,
                        ];
                        $existingAttributes[$attributeTitle][] = $attributeValue;
                    }
                }
    
                $colorData['colors'][$key] = [
                    'id' => $colorId,
                    'title' => $colorTitle,
                    'color_code' => $colorCode,
                ];
            } else {
                continue;
            }
        }
    
        return [
            'finalAttribute' => $finalArrayData,
            'colorsFinalData' => collect($colorData['colors'] ?? [])->unique('id'),
            'stockKeys' => collect($stocksKey)->unique('id')
        ];
    }
    public function testProductDetail(Request $request)
    {
        $product = Product::select('id','slug')->where('slug', $request->slug)->first();
        $product_stocks = ProductStock::where('product_id',$product->id)->get();
        $stockData = [];
        foreach ($product_stocks as $stock) {
            $stockWays = StockWays::where('stock_id', $stock->id)->get();
            foreach($stockWays as $key => $stockway)
            {   
                $stockId = $stockway->stock_id;

                if (!isset($stockData[$stockId])) {
                    $stockData[$stockId] = [
                        'product_id' => $product->id,
                        'slug' => $product->slug,
                        'color_id' => $stock->color_id,
                        'price' => $stock->price,
                        'special_price' => $stock->special_price,
                        'quantity' => $stock->quantity,
                        'stock_id' => $stockway->stock_id,
                        'stock_ways' => []
                    ];
                }
    
                $stockData[$stockId]['stock_ways'][] = [
                    'key' => $stockway->key,
                    'title' => $stockway->categoryAttribute->title,
                    'value' => $stockway->value,
                    'price' => $stockway->stock->price
                ];
            }
        }
        $stockData = array_values($stockData);

        return response()->json(['status'=>200, 'data'=>$stockData]);
    }

    public function getAllAttributePrice($product)
    {

        $keys = [];
        $stock_ways = collect($product->stockways)->groupBy('stock_id')->toArray();
        $stocks = $product->stocks;
        $paired_values = [];
        $keys = [];
        $colors = [];
        $color = [];
        $price = 0;
        $first_available = [];
        foreach ($stock_ways as $index => $stock_way) {
            foreach ($stock_way as $way) {
                $categoryAttribute = CategoryAttribute::find($way['key']);
                $key = [
                    'id' => $way['key'],
                    'title' => ($categoryAttribute) ? $categoryAttribute->title : "Null",
                ];
                $keys[] = $key;
                $paired_values[$way['key']][] = $way['value'];
                if (array_key_first($stock_ways) == $index) {
                    $first_available[$way['key']] = $way;
                }
            }
        }

        foreach ($stocks as $index => $stock) {
            $color = [
                'id' => $stock->againColor->id,
                'title' => $stock->againColor->title,
            ];
            $colors[] = $color;
            $color = $stock->againColor->id;
            if (array_key_first(collect($stocks)->toArray()) == $index) {
                if ($stock['special_price'] != null) {
                    $price = $stock->special_price;
                } else {
                    $price = $stock->price;
                }
            }
        }

        $refine_paired_values = collect($paired_values)->map(function ($row, $index) {
            return collect($row)->sort()->unique()->toArray();
        })->unique()->toArray();
        $keys = collect($keys)->unique()->toArray();
        $colors = collect($colors)->unique()->toArray();
        $keys = collect($keys)->unique()->toArray();

        $productAttribute = new ProductAttributeDetailController();
        $parameters = [
            'product_id' => $product->id,
            'color' => $stocks[0]->color_id,
            'type' => 'change_color',
        ];
        $request = new Request($parameters);
        $selected_data = $productAttribute->index($request);
        $final_data = [
            'keys' => $keys,
            'values' => $refine_paired_values,
            'first_available' => $first_available,
            'colors' => collect($colors)->sort()->toArray(),
            'color' => $stocks[0]->color_id,
            'price' => $price,
            'selected_data' => $selected_data,
            'varient_id' => $stocks[0]->id
        ];
        return $final_data;
    }

    public function getAllProductdetailColor($product)
    {
        $keys = [];
        $stock_ways = collect($product->stockways)->groupBy('stock_id')->toArray();
        $stocks = $product->stocks;
        $paired_values = [];
        $keys = [];
        $colors = [];
        $color = [];
        $price = 0;
        $first_available = [];
        foreach ($stock_ways as $index => $stock_way) {
            foreach ($stock_way as $way) {
                $categoryAttribute = CategoryAttribute::find($way['key']);
                $key = [
                    'id' => $way['key'],
                    'title' => ($categoryAttribute) ? $categoryAttribute->title : "Null",
                ];
                $keys[] = $key;
                $paired_values[$way['key']][] = $way['value'];
                if (array_key_first($stock_ways) == $index) {
                    $first_available[$way['key']] = $way;
                }
            }
        }

        foreach ($stocks as $index => $stock) {
            $color = [
                'id' => $stock->againColor->id,
                'title' => $stock->againColor->title,
            ];
            $colors[] = $color;
            $color = $stock->againColor->id;
            if (array_key_first(collect($stocks)->toArray()) == $index) {
                if ($stock['special_price'] != null) {
                    $price = $stock->special_price;
                } else {
                    $price = $stock->price;
                }
            }
        }

        $refine_paired_values = collect($paired_values)->map(function ($row, $index) {
            return collect($row)->sort()->unique()->toArray();
        })->unique()->toArray();
        $keys = collect($keys)->unique()->toArray();
        $colors = collect($colors)->unique()->toArray();
        $keys = collect($keys)->unique()->toArray();

        $productAttribute = new ProductAttributeDetailController();
        $parameters = [
            'product_id' => $product->id,
            'color' => $stocks[0]->color_id,
            'type' => 'change_color',
        ];
        $request = new Request($parameters);
        $selected_data = $productAttribute->index($request);
        $final_data = [
            'keys' => $keys,
            'values' => $refine_paired_values,
            'first_available' => $first_available,
            'colors' => collect($colors)->sort()->toArray(),
            'color' => $stocks[0]->color_id,
            'price' => $price,
            'selected_data' => $selected_data,
            'varient_id' => $stocks[0]->id
        ];
        return $final_data;
    }

    public function productAttribute(Request $request)
    {
        $arr = [];
        try {
            $product = Product::where('id', $request->product_id)->first();
            if (!$product) {
                $response = [
                    'error' => true,
                    'data' => null,
                    'msg' => 'Product Not Found !!'
                ];
                return response()->json($response, 500);
            }
            $product_stocks = ProductStock::where('product_id', $request->product_id)->where('color_id', $request->color_id)->get();
            if (count($product_stocks) < 1) {
                $response = [
                    'error' => true,
                    'data' => null,
                    'msg' => 'Sorry Something Went Wrong !!'
                ];
                return response()->json($response, 500);
            }
            $attribute_count = 0;
            $items = 0;
                                        
            foreach ($product_stocks as $product_stock) {
                $arr[$attribute_count]['id'] = $product_stock->id;
                foreach ($product_stock->getStock as $stkey => $st) {
                    $arr[$attribute_count][$st->getOption->title] = $st->value;
                }
                
                $arr[$attribute_count]['color'] = $product_stock->getColor->colorCode;
                $arr[$attribute_count]['price'] = $product_stock->price;
                $arr[$attribute_count]['special_price'] = $product_stock->special_price;
                if(getOfferProduct($product,$product_stock) !=null)
                {
                    $arr[$attribute_count]['special_price'] = getOfferProduct($product,$product_stock);
                }
                $arr[$attribute_count]['quantity'] = $product_stock->quantity ?? 0;
                $arr[$attribute_count]['free_items'] = $product_stock->free_items;
                foreach ($product_stock->getStock as $st) {
                    $arr[$attribute_count]['items_id'][$items][$st->getOption->title] = $st->getOption->id;
                }
                $arr[$attribute_count]['items_id'][$items]['color'] = $product_stock->color_id;
                $attribute_count++;
            }

            $response = [
                'error' => false,
                'data' => $arr,
                'msg' => 'Product Attribute'
            ];

            return response()->json($response, 200);
        } catch (\Exception $ex) {
            $response = [
                'error' => true,
                'data' => null,
                'msg' => $ex->getMessage()
            ];

            return response()->json($response, 200);
        }
    }
}
