<?php

namespace App\Http\Controllers\Frontend;

use App\Data\Product\ProductDetailData;
use App\Helpers\PaginationHelper;
use App\Enum\Order\OrderStatusEnum;
use App\Models\{
    Advertisement,
    AdvertisementPosition,
    Tag,
    RetailerOfferSection,
    RFQ,
    Coupon,
    Cart,
    Brand,
    User,
    Color,
    Local,
    Review,
    Slider,
    Country,
    CartAssets,
    CategoryAttribute,
    City,
    Faq,
    GetInquiry,
    Inquiry,
    Location,
    Province,
    Product,
    Order,
    OrderAsset,
    OrderStock,
    ProductStock,
    UserShippingAddress,
    Wishlist as WishList,
    Menu,
    New_Customer,
    Position,
    ProductCity,
    SelectedProduct,
    seller as Seller
};
use Illuminate\Support\Carbon;
use App\Data\Cart\Item\CartItemCheckoutData;
use App\Mail\OrderConfirm;
use App\Models\Category;
use App\Models\District;
use App\Models\DeliveryRoute;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\QuestionAnswer;
use App\Models\UserBillingAddress;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ProductAttributeDetailController;
use App\Mail\OrderConfirm as MailOrderConfirm;
use App\Models\Admin\Coupon\Customer\CustomerCoupon;
use App\Models\Admin\Offer\TopOffer;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Admin\Product\Featured\FeaturedSection;
use Symfony\Component\Console\Question\Question;
use App\Models\Order\Seller\SellerOrder;
use App\Models\Order\Seller\ProductSellerOrder;
use Illuminate\Support\Facades\DB;
use App\Actions\Seller\SellerOrderAction;
use Illuminate\Support\Facades\Auth;
use App\Actions\Frontend\DefaultCharge;
use App\Enum\Menu\MenuTypeEnum;
use App\Events\LogEvent;
use App\Actions\Notification\NotificationAction;
use App\Jobs\InquiryEmailJob;
use App\Mail\GetInquiryMail;
use App\Models\Admin\TopCategory\TopCategory;
use App\Models\Order\OrderStatus;
use Exception;
use Illuminate\Support\Facades\Validator;
use App\Traits\CommentTrait;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Setting;
use App\Mail\InquiryMail;
use App\Actions\Product\ProductFormAction;
class FrontendController extends Controller
{
    use CommentTrait;
    protected $folderName = 'frontend.';
    protected $order = null;
    protected $order_asset = null;
    protected $cart = null;
    protected $seller_order = null;
    protected $product_seller_order = null;
    protected $seller_order_action = null;
    protected $default_charge = null;
    protected $questionanswer = null;
    public function __construct(QuestionAnswer $questionanswer, Order $order, OrderAsset $order_asset, Cart $cart, SellerOrder $seller_order, ProductSellerOrder $product_seller_order, SellerOrderAction $seller_order_action, DefaultCharge $default_charge)
    {
        $this->order = $order;
        $this->order_asset = $order_asset;
        $this->cart = $cart;
        $this->seller_order = $seller_order;
        $this->product_seller_order = $product_seller_order;
        $this->seller_order_action = $seller_order_action;
        $this->default_charge = $default_charge;
        $this->questionanswer = $questionanswer;
    }


    public function newMail()
    {
        return view('frontend.emails.customer.newmail');
    }
    public function index(Request $request)
    {

        if (Session::get('city')) {
            $sessionCity = Session::get('city');
            $currentCity = City::where('city_name', 'LIKE', '%' . $sessionCity . '%')->first();
        } else {

            $currentCity = City::find(146);  // Development -> to comment in production

        }

        $loginUser=auth()->guard('customer')->user();

        
        // dd($loginUser);
        $products = Product::with('images', 'stocks')
            ->select('id', 'name', 'slug', 'rating', 'product_for')
            ->where('status', 1)
            ->where('publishStatus', '1')
            ->when($loginUser, function ($query, $user) {
                $searchItemValue = $user->wholeseller ? '2' : '1';
                if($query->first()->product_for !='3')
                {
                    return $query->where('product_for', $searchItemValue);
                }
                return $query;
            })
            // ->whereHas('city', function ($q) use ($currentCity) {
            //     $q->where('cities.city_name', $currentCity->city_name);
            // })
            ->latest()
            ->get();
            // dd($products);
        $order_asset = OrderAsset::take(3)->latest()->get();
        $order_product = $order_asset->map(function ($item) {
            return $item->product_id;
        });
        $final_product_id = collect($order_product)->unique();
        $top_sells = Product::whereIn('id', $final_product_id)->with('images', 'category', 'features', 'brand', 'colors', 'sizes', 'stocks', 'stockways', 'productTags', 'attributes', 'dangerousGoods')->where('status', 1)->where('publishStatus', '1')->take(10)
        ->when($loginUser, function ($query, $user) {
            $searchItemValue = $user->wholeseller ? '2' : '1';
            if($query->first() !=null){
                if($query->first()->product_for !='3')
                {
                    return $query->where('product_for', $searchItemValue);
                }
            }
            return $query;
        })->get();
        $heavy_discounted_product = [];
        foreach ($products as $product) {
            $max_discount = 0;
            foreach ($product->stocks as $pro) {
                if ($pro->special_price != null) {
                    $discount = $pro->price - $pro->special_price;
                    $per = ($discount / $pro->price) * 100;
                    $per = round($per, 2);
                    if ($per > $max_discount) {
                        $max_discount = $per;
                    }
                }
            }
            if ($max_discount > 0) {
                $product->setAttribute('discount', $max_discount);
                array_push($heavy_discounted_product, $product);
            }
        }
        $heavy_discounted_product = array_slice($heavy_discounted_product, 0, 2);
        $new_arrivals = Product::with('images', 'stocks')
            ->select('id', 'name', 'slug', 'rating', 'product_for')
            ->where('status', 1)
            ->where('publishStatus', '1')
            // ->when($loginUser, function ($query, $user) {
            //     $searchItemValue = $user->wholeseller ? '2' : '1';
            //     if($query->first()->product_for =='3')
            //     {
            //         return $query;
            //     }else{
            //         return $query->where('product_for', $searchItemValue);
            //     }
            // })
            // ->whereHas('city', function ($q) use ($currentCity) {
            //     $q->where('cities.city_name', $currentCity->city_name); 
            // })
            ->latest()
            ->get();
        $wholeSeller=auth()->guard('customer')->user() ? auth()->guard('customer')->user()->wholeseller : null;
        if(!$wholeSeller || $wholeSeller !=true){
            $new_arrivals=$new_arrivals->where('product_for','!=','2');
        }else{
            $new_arrivals=$new_arrivals->where('product_for','!=','1');
        }
        $sliders = Slider::select('image', 'link')->where('publish_status', '=', '1')->orderBy('order', 'ASC')->get();
        $countries = Country::where('status', '=', '1')->get();
        $total_RFQ = RFQ::count();
        $homeCategory = TopCategory::where('status', 1)->limit(3)->orderBy('id', 'DESC')->select('category_id')->get();

        $homeCategory = collect($homeCategory)->unique();
        $cat_id = collect($homeCategory)->map(function ($item) {
            return $item->category_id;
        });
        $homeCategory = Category::whereIn('id', $cat_id)->where(['showOnHome' => '1', 'status' => '1'])->withCount(['ancestors', 'descendants'])->take(16)->with('children')->get();

        $product_features = FeaturedSection::where('status', true)->get();
        $recent_view_product_id = Session::get('pro_ids');
        if (isset($recent_view_product_id)) {
            $recent_view_product_id = array_unique($recent_view_product_id);
        } else {
            $recent_view_product_id = [];
        }
        $recommended_products = Product::with('images', 'stocks')
            ->select('id', 'name', 'slug', 'rating', 'product_for')
            // ->whereHas('city', function ($q) use ($currentCity) {
            //     $q->where('cities.city_name', $currentCity->city_name);
            // })
            ->where('status', 1)
            ->where('publishStatus', '1')
            ->when($loginUser, function ($query, $user) {
                $searchItemValue = $user->wholeseller ? '2' : '1';
                if($query->first()->product_for !='3')
                {
                    return $query->where('product_for', $searchItemValue);
                }
                return $query;
            })
            ->inRandomOrder()
            ->orderBy('created_at', 'DESC')
            ->limit(15)
            ->get();
            if(!$wholeSeller || $wholeSeller !=true){
                $recommended_products=$recommended_products->where('product_for','!=','2');
            }else{
                $recommended_products=$recommended_products->where('product_for','!=','1');
            }
            // dd($recommended_products);
        $position = AdvertisementPosition::orderBy('position_id', 'ASC')->with('ads', function ($q) {
            return $q->where('ad_type', 'General');
        })->get()->map(function ($item) {
            return  $item->ads;
        });
        $position = collect($position)->whereNotNull();


        $special_offers = Product::with('images', 'stocks')
            ->select('id', 'name', 'slug', 'rating', 'product_for')
            ->where('status', 1)
            ->where('publishStatus', '1')
            ->when($loginUser, function ($query, $user) {
                $searchItemValue = $user->wholeseller ? '2' : '1';
                if($query->first()->product_for !='3')
                {
                    return $query->where('product_for', $searchItemValue);
                }
                return $query;
            })
            // ->whereHas('city', function ($q) use ($currentCity) {
            //     $q->where('cities.city_name', $currentCity->city_name);
            // })
            ->whereHas('category', function ($q) {
                return $q->where('slug', 'LIKE', '%' . 'special' . '%');
            })
            ->latest()
            ->get();
        $skip_ads = Advertisement::select('url', 'image')->where('ad_type', 'Skip Ad')->where('status', 'active')->latest()->get();

        if (empty($recent_view_product_id)) {
           
            $recent_view_products = collect();
        } else {
           
            $recent_view_products = Product::with('images', 'stocks')
                ->select('id', 'name', 'slug', 'rating', 'product_for')
                ->where('status', 1)
                ->where('publishStatus', '1')
                ->whereIn('id', $recent_view_product_id)
                ->limit(10)
                ->latest()
                ->get();
        }
        
            $selectedProduct=SelectedProduct::get()->pluck('product_id');
            $selectedProductItem=null;
            if($selectedProduct && count($selectedProduct)>0){
                $selectedProductItem=Product::whereIn('id',$selectedProduct)->with('images', 'stocks')
                ->select('id', 'name', 'slug', 'rating', 'product_for')
                ->where('status', 1)
                ->where('publishStatus', '1')
                ->get();
            }
            if(!$wholeSeller || $wholeSeller !=true){
                $selectedProductItem=$selectedProductItem->where('product_for','!=','2');
            }else{
                $selectedProductItem=$selectedProductItem->where('product_for','!=','1');
            }
            $advertisementsData=Advertisement::select('url', 'image')->where('ad_type', 'General')->where('status', 'active')->latest()->get();
        return view('frontend.index', compact('advertisementsData','selectedProductItem','top_sells', 'new_arrivals',  'special_offers', 'heavy_discounted_product', 'total_RFQ', 'recommended_products', 'product_features', 'sliders', 'products', 'recent_view_products', 'countries', 'position',  'skip_ads'));
    }

    public function popularProductList(Request $request)
    {

        $order_asset = OrderAsset::get();
        $order_product = $order_asset->map(function ($item) {
            return $item->product_id;
        });
        $final_product = collect($order_product)->unique();
        $products = Product::whereIn('id', $final_product)->with('images', 'category', 'features', 'brand', 'colors', 'sizes', 'stocks', 'stockways', 'productTags', 'attributes', 'dangerousGoods')->where('status', '1')->where('publishStatus', 1)->get();

        $brand_id = [];
        $color_id = [];
        foreach ($products as $data) {
            if ($data->brand_id != null) {
                $brand_id[] = $data->brand_id;
            }
            foreach ($data->stocks as $color) {
                $color_id[] = $color->color_id;
            }
        }
        $brand_id = collect($brand_id)->unique();
        $color_id = collect($color_id)->unique();
        $colors = Color::whereIn('id', $color_id)->get();
        $brands = Brand::whereIn('id', $brand_id)->get();
        LogEvent::dispatch('Popular Product List View', 'Product List View', route('popularproduct.list'));

        $url = route('popularproduct.list');
        $products = PaginationHelper::paginate(collect($products), 40)->withPath($url);


        return view('frontend.popularproductlist', compact('colors', 'brands', 'products'));
    }

    public function specialProductList()
    {
    }

    public function newProductList(request $request)
    {

        $brand_id = [];
        $color_id = [];
        $loginUser=auth()->guard('customer')->user();
        // $products = Product::with('images', 'category', 'features', 'brand', 'colors', 'sizes', 'stocks', 'stockways', 'productTags', 'attributes', 'dangerousGoods')->where('status', 1)->where('publishStatus', '1')->orderBy('created_at', 'DESC')
        // ->when($loginUser, function ($query, $user) {
        //     $searchItemValue = $user->wholeseller ? '2' : '1';
        //     if($query->first()->product_for !='3')
        //     {
        //         return $query->where('product_for', $searchItemValue);
        //     }
        //     return $query;
        // })
        // ->get();


  $product =  Product::where('product_for', 1)->where('status',1)->get();
        $products = Product::with('images', 'stocks')
        ->select('id', 'name', 'slug', 'rating', 'product_for')
        ->where('status', 1)
        ->where('publishStatus', '1')
        ->when($loginUser, function ($query, $user) {
            $searchItemValue = $user->wholeseller ? '2' : '1';
            // dd( $searchItemValue);
            // if($query->first()->product_for !='3')
            // {
                return $query->where('product_for', $searchItemValue);
            // }
            // return $query;
        })
        // ->whereHas('city', function ($q) use ($currentCity) {
        //     $q->where('cities.city_name', $currentCity->city_name); 
        // })
        ->latest()
        ->get();
        // dd('tefdsf');


        $wholeSeller=auth()->guard('customer')->user() ? auth()->guard('customer')->user()->wholeseller : null;
        if(!$wholeSeller || $wholeSeller !=true){
            $products=$products->where('product_for','!=','2');
        }
        foreach ($products as $data) {
            if ($data->brand_id != null) {
                $brand_id[] = $data->brand_id;
            }
            foreach ($data->stocks as $color) {
                $color_id[] = $color->color_id;
            }
        }
        $brand_id = collect($brand_id)->unique();
        $color_id = collect($color_id)->unique();
        $colors = Color::whereIn('id', $color_id)->get();
        $brands = Brand::whereIn('id', $brand_id)->get();
        LogEvent::dispatch('New Product List View', 'New List View', route('popularproduct.list'));
        $url = route('newproduct.list');
        $products = PaginationHelper::paginate(collect($products), 40)->withPath($url);
        return view('frontend.newproductlist', compact('colors', 'brands', 'products'));
    }

    public function newSelectedList(request $request)
    {

        $brand_id = [];
        $color_id = [];
        $loginUser=auth()->guard('customer')->user();
        $selectedProduct=SelectedProduct::get()->pluck('product_id');
            $products=null;
            if($selectedProduct && count($selectedProduct)>0){
                $products=Product::whereIn('id',$selectedProduct)->with('images', 'stocks')
                ->select('id', 'name', 'slug', 'rating', 'product_for')
                ->where('status', 1)
                ->where('publishStatus', '1')
                
                ->get();
            }
            $wholeSeller=auth()->guard('customer')->user() ? auth()->guard('customer')->user()->wholeseller : null;
            if(!$wholeSeller || $wholeSeller !=true){
                $products=$products->where('product_for','!=','2');
            }
        // $products = Product::with('images', 'category', 'features', 'brand', 'colors', 'sizes', 'stocks', 'stockways', 'productTags', 'attributes', 'dangerousGoods')->where('status', 1)->where('publishStatus', '1')->orderBy('created_at', 'DESC')
        // ->when($loginUser, function ($query, $user) {
        //     $searchItemValue = $user->wholeseller ? '2' : '1';
        //     if($query->first()->product_for !='3')
        //     {
        //         return $query->where('product_for', $searchItemValue);
        //     }
        //     return $query;
        // })
        // ->get();
        foreach ($products as $data) {
            if ($data->brand_id != null) {
                $brand_id[] = $data->brand_id;
            }
            foreach ($data->stocks as $color) {
                $color_id[] = $color->color_id;
            }
        }
        $brand_id = collect($brand_id)->unique();
        $color_id = collect($color_id)->unique();
        $colors = Color::whereIn('id', $color_id)->get();
        $brands = Brand::whereIn('id', $brand_id)->get();
        LogEvent::dispatch('New Product List View', 'New List View', route('popularproduct.list'));
        $url = route('newproduct.list');
        $products = PaginationHelper::paginate(collect($products), 40)->withPath($url);
        return view('frontend.newproductlist', compact('colors', 'brands', 'products'));
    }

    public function specialOfferList()
    {

        $products = Product::with('images', 'category', 'features', 'brand', 'colors', 'sizes', 'stocks', 'stockways', 'productTags', 'attributes', 'dangerousGoods')->where('status', 1)->where('publishStatus', '1')->orderBy('created_at', 'DESC')
            ->whereHas('category', function ($q) {
                return $q->where('slug', 'LIKE', '%' . 'special' . '%');
            })
            ->latest()
            ->get();

        // dd($products);
        $brand_id = [];
        $color_id = [];
        // $products = Product::with('images', 'category', 'features', 'brand', 'colors', 'sizes', 'stocks', 'stockways', 'productTags', 'attributes', 'dangerousGoods')->where('status', 1)->where('publishStatus', '1')->orderBy('created_at', 'DESC')->get();
        foreach ($products as $data) {

            if ($data->brand_id != null) {
                $brand_id[] = $data->brand_id;
            }
            foreach ($data->stocks as $color) {
                $color_id[] = $color->color_id;
            }
        }
        $brand_id = collect($brand_id)->unique();
        $color_id = collect($color_id)->unique();
        $colors = Color::whereIn('id', $color_id)->get();
        $brands = Brand::whereIn('id', $brand_id)->get();
        LogEvent::dispatch('Special Product List View', 'Special Product List', route('popularproduct.list'));
        $url = route('popularproduct.list');
        $products = PaginationHelper::paginate(collect($products), 40)->withPath($url);
        return view('frontend.special_offerlist', compact('colors', 'brands', 'products'));
    }

    public function childrenCategory($id)
    {
        $category = Category::find($id);
        $children = Category::orWhereDescendantOf($category)->withDepth->get();
        return response()->json($children);
    }

    public function getDetails($slug)
    {
        $product = Product::with('city')->whereSlug($slug)->firstOrFail();
        // dd($product->images);

        $product_stocks = ProductStock::where('product_id', $product->id)->get();

        $required_color_ids = $product_stocks->map(function ($product_stock) {
            return $product_stock->color_id;
        })->unique()->values();






        $seller = Seller::where('id', $product->seller_id)->first();
        $sameProduct = Product::where('category_id', $product->category_id)
            ->where('seller_id', $seller->id ?? User::first()->id)
            ->where('id', '!=', $product->id)
            ->limit(4)
            ->orderBy('created_at', 'DESC')
            ->get();
        // dd($product);
        if ($product->seller_id != null) {
            $seller = seller::where('id', $product->seller_id)->first();
        }
        $data = (new ProductDetailData())->getDetails($slug);
        $final_data = $this->getAllAttributePrice($product);
        // dd($final_data);
        // dd($final_data['color_array']);
        $data['final_data'] = $final_data;

        $provinces = Province::where('publishStatus', true)->get();
        if (auth()->guard('customer')->user()) {
            $shipping_address = UserShippingAddress::where('user_id', auth()->guard('customer')->user()->id)->latest()->first();
        }
        $meta = [
            'meta_title' => $product->meta_title,
            'meta_description' => $product->meta_description,
            'meta_keyword' => $product->meta_keywords,
            'og_image' => $product->og_image,
        ];

        $charge_data = $this->default_charge->getCharge($provinces);
        $user = auth()->guard('customer')->user();
        if ($user) {
            $all_question = $this->questionanswer->where('product_id', $product->id)->where('status', 1)->whereNull('parent_id')->orderBy('id', 'DESC')->get();
            $user_specific_question = $this->questionanswer->where('product_id', $product->id)->whereNull('parent_id')->where('customer_id', $user->id)->where('status', 0)->orderBy('id', 'DESC')->get();
            $comment = $this->getCommentList($all_question, $user_specific_question);
        } else {
            $all_question = $this->questionanswer->where('product_id', $product->id)->where('status', 1)->whereNull('parent_id')->orderBy('id', 'DESC')->get();
            $comment = $this->getCommentList($all_question);
        }
        LogEvent::dispatch('Product View', 'Product View', route('product.details', $product->slug));
        if (session('detail_info_address') != null) {
            $new_province = Province::where('eng_name', session('detail_info_address')['province'])->where('publishStatus', true)->first();
            $new_district = District::where('np_name', session('detail_info_address')['district'])->first();
        } else {
            $new_province = $provinces[0];
            $new_district = $provinces[0]->districts[0];
        }
        $productBarCode = $data['product']->name ?? '';

        $productDetails = 'details';
        // $attributeData=$this->getProductAttribute($product->slug);
        $attributeData = $this->getAttributeDataAll($product);
        if (auth()->guard('customer')->user()) {
            return view('frontend.product.details', $data, compact('required_color_ids'))
                ->with('meta', $meta)->with('provinces', $provinces)->with('shipping_address', $shipping_address)
                ->with('default_province', @$charge_data['province'])
                ->with('default_district', @$charge_data['district'])
                ->with('default_area', @$charge_data['area'])
                ->with('default_charge', @$charge_data['charge'])
                ->with('seller', @$seller)
                ->with('questionAnswer', $comment)
                ->with('new_province', $new_province)
                ->with('new_district', $new_district)
                ->with('sameProduct', $sameProduct)
                ->with('productId', $product->id)
                ->with('productDetails', $productDetails ?? '')
                ->with('productBarCode', $productBarCode ?? '')
                ->with('attributeData', $attributeData ?? '')
                ->with('productSlug', $product->slug ?? '');
        } else {
            return view('frontend.product.details', $data, compact('required_color_ids'))
                ->with('meta', $meta)->with('provinces', $provinces)
                ->with('default_province', @$charge_data['province'])
                ->with('default_district', @$charge_data['district'])
                ->with('default_area', @$charge_data['area'])
                ->with('default_charge', @$charge_data['charge'])
                ->with('seller', @$seller)
                ->with('questionAnswer', $comment)
                ->with('new_province', $new_province)
                ->with('new_district', $new_district)
                ->with('sameProduct', $sameProduct)
                ->with('productId', $product->id)
                ->with('productDetails', $productDetails ?? '')
                ->with('productBarCode', $productBarCode ?? '')
                ->with('attributeData', $attributeData ?? '')
                ->with('productSlug', $product->slug ?? '');
        }
    }

    public function updateAttributeData(Request $request){
        $colorData=$request->colorData;
        $colorCodeValue=$request->colorCode;
        $productId=$request->productId;
        $attributeData=$request->filterData;
        $stocks=$request->stocks;
        return view('frontend.updateAttribute')->with('attributeData',$attributeData)->with('stocks',$stocks)->with('productId',$productId)->with('colorData',$colorData)->with('colorCodeValue',$colorCodeValue);
    }

    public function getAttributeDataAll($product) {
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
                $delPrice=null;
                if($stockData->special_price){
                    $delPrice=$stockData->price;
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
                foreach ($finalArrayData[$colorCode]['attributes'] as $existingAttribute) {
                    $existingAttributes[$existingAttribute['title']][] = $existingAttribute['value'];
                }
    
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
                            'delPrice'=>$delPrice
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
    
    
    // public function getAllAttributePrice($product)
    // {
    //     $keys = [];
    //     $stock_ways = collect($product->stockways)->groupBy('stock_id')->toArray();
    //     $stocks = $product->stocks;
    //     $paired_values = [];
    //     $keys = [];
    //     $colors = [];
    //     $color = [];
    //     $price = 0;
    //     $first_available = [];
    //     foreach ($stock_ways as $index => $stock_way) {
    //         foreach ($stock_way as $way) {
    //             $categoryAttribute = CategoryAttribute::find($way['key']);
    //             $key = [
    //                 'id' => $way['key'],
    //                 'title' => ($categoryAttribute) ? $categoryAttribute->title : "Null",
    //             ];
    //             $keys[] = $key;
    //             $paired_values[$way['key']][] = $way['value'];
    //             if (array_key_first($stock_ways) == $index) {
    //                 $first_available[$way['key']] = $way;
    //             }
    //         }
    //     }
    //     foreach ($stocks as $index => $stock) {
    //         $color = [
    //             'id' => $stock->againColor->id,
    //             'title' => $stock->againColor->title,
    //         ];
    //         $colors[] = $color;
    //         $color = $stock->againColor->id;
    //         if (array_key_first(collect($stocks)->toArray()) == $index) {
    //             if ($stock['special_price'] != null) {
    //                 $price = $stock->special_price;
    //             } else {
    //                 $price = $stock->price;
    //             }
    //         }
    //     }
    //     $refine_paired_values = collect($paired_values)->map(function ($row, $index) {
    //         return collect($row)->sort()->unique()->toArray();
    //     })->unique()->toArray();
    //     $keys = collect($keys)->unique()->toArray();
    //     $colors = collect($colors)->unique()->toArray();
    //     $keys = collect($keys)->unique()->toArray();
    //     $productAttribute = new ProductAttributeDetailController();
    //     $parameters = [
    //         'product_id' => $product->id,
    //         'color' => $stocks[0]->color_id,
    //         'type' => 'change_color',
    //     ];
    //     $firstSelecetedColorId = collect($stocks)->pluck('color_id')->unique();
    //     foreach ($firstSelecetedColorId as $finalColor) {
    //         $showColorCode[] = Color::where('id', $finalColor)->first();
    //     }
    //     // $showColorCode[]=Color::where('id', $)->first()
    //     // dd($firstSelecetedColorId,$stocks[0]);
    //     $request = new Request($parameters);
    //     $selected_data = $productAttribute->index($request);
    //     $final_data = [
    //         'keys' => $keys,
    //         'values' => $refine_paired_values,
    //         'first_available' => $first_available,
    //         'colors' => collect($colors)->sort()->toArray(),
    //         'color' => $stocks[0]->color_id,
    //         // 'colorCode' => Color::where('id', $stocks[0]->color_id)->first()->colorCode ?? null,
    //         'colorCode' => collect($showColorCode)->pluck('colorCode') ?? null,
    //         'colorCodeId' => collect($showColorCode)->pluck('id') ?? null,
    //         'price' => $price,
    //         'selected_data' => $selected_data,
    //         'varient_id' => $stocks[0]->id
    //     ];
    //     // dd($final_data);
    //     return $final_data;
    // }


    public function getAllAttributePrice($product)
    {
        $keys = [];
        $stock_ways = collect($product->stockways)->groupBy('stock_id')->toArray();
        $stocks = $product->stocks;
        $paired_values = [];
        $colors = [];
        $color = [];
        $price = 0;
        $first_available = [];
    
        // Processing stock ways
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
    
        // Processing stocks and setting the price
        foreach ($stocks as $index => $stock) {
            $color = [
                'id' => $stock->againColor->id,
                'title' => $stock->againColor->title,
            ];
            $colors[] = $color;
            if (array_key_first(collect($stocks)->toArray()) == $index) {
                if ($stock['special_price'] != null) {
                    $price = $stock->special_price;
                } else {
                    $price = $stock->price;
                }
            }
        }
        // dd($price);
    
        // Refining paired values
        $refine_paired_values = collect($paired_values)->map(function ($row) {
            return collect($row)->sort()->unique()->toArray();
        })->unique()->toArray();
    
        // Making unique keys and colors
        $keys = collect($keys)->unique()->toArray();
        $colors = collect($colors)->unique()->toArray();
    
        // Handling product attributes for selected data
        $productAttribute = new ProductAttributeDetailController();
        $parameters = [
            'product_id' => $product->id,
            'color' => $stocks[0]->color_id,
            'type' => 'change_color',
        ];
    
        $firstSelecetedColorId = collect($stocks)->pluck('color_id')->unique();
        foreach ($firstSelecetedColorId as $finalColor) {
            $showColorCode[] = Color::where('id', $finalColor)->first();
        }
    
        $request = new Request($parameters);
        $selected_data = $productAttribute->index($request);
    
        
        $offer = RetailerOfferSection::whereHas('productList', function ($query) use ($product) {
            $query->where('product_id', $product->id);
        })->first(); 
    
        
        $discountedPrice = $price;
    
      
        if ($offer) {
          
            if ($offer->is_fixed == 0 && $offer->offer) {
               
                $offerAmount = $offer->offer;  
                if ($offerAmount > 0 && $discountedPrice > 0) {
                    
                    $priceWithOffer = $discountedPrice + $offerAmount;
                    
                  
                    $discountPercentage = $offer->offer; 
                    if ($discountPercentage > 0) {
                     
                        $discountedPrice = $priceWithOffer - (($discountPercentage / 100) * $priceWithOffer);
                    }
                }
            }
        }
    
      
        $final_data = [
            'keys' => $keys,
            'values' => $refine_paired_values,
            'first_available' => $first_available,
            'colors' => collect($colors)->sort()->toArray(),
            'color' => $stocks[0]->color_id,
            'colorCode' => collect($showColorCode)->pluck('colorCode') ?? null,
            'colorCodeId' => collect($showColorCode)->pluck('id') ?? null,
            'price' => $discountedPrice, 
            'selected_data' => $selected_data,
            'varient_id' => $stocks[0]->id
        ];
    
        // dd($final_data); 
    
        return $final_data;
    }


    public function getColors($row)
    {
        $colors = [];
        foreach ($row as $r) {
            $stock = ProductStock::find($r['stock_id']);
            $color = $stock->againColor->title;
            $colors[$stock['id']] = $color;
        }
        return $colors;
    }
    public function category_show($slug)
    {
        $selected_cat = Category::where('slug', $slug)->firstOrFail();
        $main_category = Category::ancestorsAndSelf(Category::where('slug', $slug)->where('status', '1')->first())->where('status', '1');
        $result = Category::descendantsAndSelf(Category::where('slug', $slug)->where('status', '1')->first())->where('status', '1');
       
        $products = [];
        $product_id = [];
        $brand_id = [];
        $color_id = [];
        $loginCustomer=auth()->guard('customer')->user();
        $searchItemValue='3';
        if($loginCustomer)
        {
            $searchItemValue = $loginCustomer->wholeseller ? '2' : '1';
        }
        foreach ($result as $product) {
            foreach ($product->products as $pro) {
                if($loginCustomer)
                {
                    if($pro->product_for !='3')
                    {
                        if($pro->product_for==$searchItemValue)
                        {
                            if ($pro->publishStatus == '1' && $pro->status == '1') {
                                if ($pro->brand_id != null) {
                                    $brand_id[] = $pro->brand_id;
                                }
                                foreach ($pro->stocks as $color_data) {
                                    $color_id[] = $color_data->color_id;
                                }
                                array_push($products, $pro);
                            }
                        }
                    }
                    else
                    {
                        if ($pro->publishStatus == '1' && $pro->status == '1') {
                            if ($pro->brand_id != null) {
                                $brand_id[] = $pro->brand_id;
                            }
                            foreach ($pro->stocks as $color_data) {
                                $color_id[] = $color_data->color_id;
                            }
                            array_push($products, $pro);
                        }
                    }
                   
                }
                else
                {
                    if ($pro->publishStatus == '1' && $pro->status == '1') {
                        if ($pro->brand_id != null) {
                            $brand_id[] = $pro->brand_id;
                        }
                        foreach ($pro->stocks as $color_data) {
                            $color_id[] = $color_data->color_id;
                        }
                        array_push($products, $pro);
                    }
                }
            }
        }
        $wholeSeller=auth()->guard('customer')->user() ? auth()->guard('customer')->user()->wholeseller : null;
        if(!$wholeSeller || $wholeSeller !=true){
            $products=collect($products)->where('product_for','!=','2');
        }else{
            $products=collect($products)->where('product_for','!=','1');
        }
        $color_id = collect($color_id)->unique();
        $colors = Color::whereIn('id', $color_id)->get();
        $brand_id = collect($brand_id)->unique();
        $brands = Brand::whereIn('id', $brand_id)->get();

        $count = count($products);
        $meta = [
            'meta_title' => $selected_cat->meta_title,
            'meta_description' => $selected_cat->meta_keywords,
            'meta_keywords' => $selected_cat->meta_description,
            'og_image' => $selected_cat->og_image
        ];

        $url = route('category.show', $slug);
        $products = PaginationHelper::paginate(collect($products), 40)->withPath($url);

        LogEvent::dispatch('Category View', 'Category View', route('category.show', $slug));
        return view($this->folderName . 'category.category', compact('products', 'colors', 'brands', 'slug', 'result', 'main_category', 'selected_cat', 'meta', 'count', 'brands'));
    }
    public function tags_show($slug)
    {
        $tag = Tag::where('slug', $slug)->get();
        $products = $tag[0]->products ?? null;
        $url = route('tags.show', $slug);
        $products = PaginationHelper::paginate(collect($products), 40)->withPath($url);


        $color_id = [];
        $brand_id = [];
        foreach ($tag[0]->products as $product) {
            if ($product->brand_id != null) {
                $brand_id[] = $product->brand_id;
            }
            foreach ($product->stocks as $stock) {
                $color_id[] = $stock->color_id;
            }
        }
        $brand_id = collect($brand_id)->unique();
        $color_id = collect($color_id)->unique();
        $brands = Brand::whereIn('id', $brand_id)->get();
        $colors = Color::whereIn('id', $color_id)->get();
        LogEvent::dispatch('Tag View', 'Tag View', route('tags.show', $slug));
        return view('frontend.tag.tag', compact('products', 'colors', 'brands', 'slug', 'tag'));
    }
    public function review(Request $request)
    {
        $request->validate([
            'rating' => 'required',
            'message' => 'required'

        ]);
        LogEvent::dispatch('Review Created', 'Review Created', route('review'));
        Review::create($request->all());
        return redirect()->back()->with('success', "your review is added");
    }

    public function general($slug)
    {
        $menu = Menu::where('slug', $slug)->first();
        // dd($menu);
        switch ($menu->menu_type) {
            case MenuTypeEnum::PAGE:
                $content = Menu::where('slug', $slug)->first();
                $meta = $content;
                if ($slug == 'faq' || $slug == 'faqs') {
                    $faqs = Faq::where('status', 1)->get();
                    return view('frontend.faq', compact('faqs', 'content', 'meta'));
                } elseif ($slug == 'contact-us' || $slug == 'contact') {
                    return view('frontend.contact', compact('content', 'meta'));
                }
                return view('frontend.general', compact('content', 'meta'));
                break;

            case MenuTypeEnum::EXTERNAL_LINK:
                return Redirect::to($menu->external_link);
                break;
            case MenuTypeEnum::CATEGORY:
                $model = $menu->model::findOrFail($menu->model_id);
                return redirect()->route('category.show', $model->slug);
                break;
            case MenuTypeEnum::TAG:
                $model = $menu->model::findOrFail($menu->model_id);
                // dd($model);
                return redirect()->route('tags.show', $model->slug);
                break;
            case MenuTypeEnum::SELLER:
                $model = $menu->model::findOrFail($menu->model_id);
                return redirect()->route('seller', $model->slug);
                break;
            case MenuTypeEnum::BRAND:
                $model = $menu->model::findOrFail($menu->model_id);
                // this must be redeveloped
                return redirect()->route('brand-front.index', $model->slug);
                break;
            case MenuTypeEnum::PRODUCT:
                $model = $menu->model::findOrFail($menu->model_id);
                return redirect()->route('getDetails', $model->slug);
                break;
            default:
                # code...
                break;
        }



        switch ($slug) {
            case "about":
                return view('frontend.about');
                break;
            case "help":
                return view('frontend.help');
                break;
            default:
                $content = Menu::where('slug', $slug)->first();
                return view('frontend.general', compact('content'));
        }
    }

    public function billingAddress(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required|max:15|regex:/^([0-9\s\-\+\(\)]*)$/',
            'province' => 'required',
            'district' => 'required',
            'area' => 'required',
        ]);

        // 'area', 'additional_address'
        $data = $request->except(['province', 'district']);
        $data['province'] = Province::where('id', $request->province)->value('eng_name');
        $data['district'] = District::where('dist_id', $request->district)->value('np_name');
        $data['area'] = City::where('id', $request->area)->value('city_name');
        // $data['additional_address'] = 
        $data['future_use'] = 1;
        $previous_user_in_billing = UserBillingAddress::where('user_id', auth()->guard('customer')->user()->id)->first();
        // $previous_user_in_billing->updateOrCreate($data);
        if ($previous_user_in_billing != null) {
            LogEvent::dispatch('BillingAddress Added', 'BillingAddress Added', route('billingAddress'));
            $previous_user_in_billing->update($data);
        } else {
            UserBillingAddress::create($data);
        }
        return redirect()->back()->with('success', 'Address updated successfully');
    }



    public function updateshippingaddress(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required|max:15',
            'email' => 'required',
            'province' => 'required',
            'state' => 'required',
            'area' => 'required',
            'country'=>'required|string'
            // 'additional_address'=>'required',
        ]);

        $data = $request->except(['same', 'payment', 'province', 'district', 'area']);
        $data['province'] = $request->province;
        // $data['district'] = $request->district;
        $data['state'] = $request->state;
        // $data['area'] = City::where('id', $request->area)->value('city_name');
        $data['area'] = $request->area;
        // $data['additional_address'] = Location::where('id', $request->additional_address)->value('title');
        $data['additional_address'] = $request->additional_address;
        $data['country'] = $request->country;
        $previous_user_in_shipping = UserShippingAddress::findOrFail($id);
        LogEvent::dispatch('ShippingAddress Updated', 'ShippingAddress Updated', route('updateshippingaddress', $id));
        $previous_user_in_shipping->update($data);
        return redirect()->back()->with('success', 'Address updated successfully');
    }



    public function oneShippingAddress(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required|max:15|min:9',
            'province' => 'required',
            'district' => 'required',
            'area' => 'required',
        ]);
        $data = $request->except(['same', 'payment', 'province', 'district', 'area']);
        $data['province'] = Province::where('id', $request->province)->value('eng_name');
        $data['district'] = District::where('dist_id', $request->district)->value('np_name');
        $data['area'] = City::where('id', $request->area)->value('city_name');

        if (isset($request->coupoon_code)) {
            $coupon = Coupon::where('coupon_code', $request->coupoon_code)->first();
            $data['coupon_name'] = $coupon->title;
        }

        $previous_user_in_shipping = UserShippingAddress::where('user_id', auth()->guard('customer')->user()->id)->first();
        if (!$request->future_use) {
            $data['future_use'] = 0;
        } else {
            $previous_user_in_billing = UserBillingAddress::where('user_id', auth()->guard('customer')->user()->id)->first();
            if ($previous_user_in_billing != null) {
                $previous_user_in_billing->update(['future_use' => 1]);
            }
        }

        if ($previous_user_in_shipping == null) {
            $data['user_id'] = auth()->guard('customer')->user()->id;
            UserShippingAddress::create($data);
        } else {
            $previous_user_in_shipping = UserShippingAddress::where('user_id', auth()->guard('customer')->user()->id)->first();
            $previous_user_in_shipping->update($data);
        }



        if ($request->same) {
            $data['user_id'] = auth()->guard('customer')->user()->id;
            if ($request->future_use == 1) {
                $previous_user_in_billing = UserBillingAddress::where('user_id', auth()->guard('customer')->user()->id)->first();
                if ($previous_user_in_billing != null) {
                    $previous_user_in_billing->update($data);
                } else {
                    UserBillingAddress::create($data);
                }
            }
            $data = array_merge($data, ['b_name' => $request->name, 'b_email' => $request->email, 'b_phone' => $request->phone, 'b_province' => $data['province'], 'b_district' => $data['district'], 'b_area' => $data['area'], 'b_zip' => $request->zip, 'b_additional_address' => $request->additional_address]);
        } else {
            $previous_user_in_billing = UserBillingAddress::where('user_id', auth()->guard('customer')->user()->id)->first();
            if ($previous_user_in_billing != null) {
                $data['b_name'] = $previous_user_in_billing->name;
                $data['b_email'] = $previous_user_in_billing->email;
                $data['b_phone'] = $previous_user_in_billing->phone;
                $data['b_province'] = $previous_user_in_billing->province;
                $data['b_district'] = $previous_user_in_billing->district;
                $data['b_area'] = $previous_user_in_billing->area;
                $data['b_additional_address'] = $previous_user_in_billing->additional_address;
                $data['b_zip'] = $previous_user_in_billing->zip;
            } else {
                $user = User::where('id', auth()->guard('customer')->user()->id)->first();
                $data['b_name'] = $user->name;
                $data['b_email'] = $user->email;
                $data['b_phone'] = $user->phone;
                $data['b_province'] = $user->province;
                $data['b_district'] = $user->district;
                $data['b_area'] = $user->area;
                $data['b_additional_address'] = $user->additional_address;
                $data['b_zip'] = $user->zip;
            }
        }

        // order table ma jane aha bata
        // payment process start from here
        if ($request->payment == 'esewa') {
            $totalAmount = $request->grandTotal;
            $ref_id = "Glass Pipe-" . Str::random(9);
            $amount = $request->grandTotal - $data['shipping_charge'];
            $taxAmount = 0;
            $serviceCharge = 0;
            $shippingCharge = $data['shipping_charge'];
            $pid = $ref_id;
            $coupon_code = $request->coupoon_code;
            return view('payment.esewa', compact('coupon_code', 'totalAmount', 'amount', 'taxAmount', 'serviceCharge', 'shippingCharge', 'pid'));
        } elseif ($request->payment == "COD") {
            $ref_id = "Glass Pipe-" . Str::random(9);
            $product = Product::where("id", $request->product_id)->first();
            foreach ($product->stocks as $key => $stock) {
                if ($key == 0) {
                    if ($stock->special_price) {
                        $price = $stock->special_price;
                        $discounts = $stock->price - $stock->special_price;
                        $color = $stock->color_id;
                    } else {
                        $price = $stock->price;
                        $discounts = 0;
                        $color = $stock->color_id;
                    }
                }
            }
            $total_quantity = 1;
            $total_price = $price;
            $total_discount = $discounts;

            // if User have Coupon
            if ($request->coupoon_code) {
                $discount_type = $coupon->is_percentage;
                $discount = $coupon->discount;
                if ($discount_type == 'yes') {
                    $coupon_discount = (int) round(($total_price * $discount) / 100);
                    $total_price = (int) round($total_price - (($total_price * $discount) / 100));
                } else {
                    $currency_id = $coupon->currency_id;
                    if ($currency_id == 1) {
                        $total_price = $total_price - $discount;
                        $coupon_discount = $discount;
                    }

                    // Nepali currency ko lagi matra vako xa
                }

                $total_discount = $total_discount + $coupon_discount;
            }

            // dd($total_discount);
            // create order
            $variable = ['user_id' => auth()->guard('customer')->user()->id, 'total_quantity' => $total_quantity, 'total_price' => $total_price, 'total_discount' => $total_discount, 'ref_id' => $ref_id, 'pending' => 1, 'payment_status' => 0];
            $final_data = array_merge($data, $variable);
            unset($final_data['future_use']);
            Order::create($final_data);

            // create order assets
            $order_assets = Order::where('ref_id', $ref_id)->first();
            $order_id = $order_assets->id;


            $product_id = $request->product_id;
            $product_name = $product->name;
            $price = $total_price;
            $qty = 1;
            $sub_total_price = $total_price;
            $color = $color;
            $discount = $total_discount;

            // update stock
            $product_quantity = ProductStock::where(['product_id' => $product_id])->value('quantity');
            $latest_quantity = $product_quantity - $qty;
            $product = ProductStock::where(['product_id' => $product_id])->first();
            $product->update(['quantity' => $latest_quantity]);

            $product_total_sell = Product::where('id', $product_id)->value('total_sell');
            $new_total_sell = $product_total_sell + $qty;
            $raw_product = Product::where('id', $product_id)->first();
            $raw_product->update(['total_sell' => $new_total_sell]);

            OrderAsset::create(['order_id' => $order_id, 'product_id' => $product_id, 'product_name' => $product_name, 'price' => $price, 'qty' => $qty, 'sub_total_price' => $sub_total_price, 'color' => $color, 'discount' => $discount]);
            // create order Stock Here

            // mail to customer
            // $pdf = PDF::loadView('emails.customer.customercheckoutmailpdf', compact('orders', 'payment', 'setting'));
            $info = ['ref_id' => $ref_id, 'total_price' => $total_price, 'payment_method' => 'COD'];
            Mail::to($data['email'])->send(new OrderConfirm($data, $info));
            return redirect()->route('Corder')->with('success', "Your Order is Placed");
        }
    }

    public function getDistrict(Request $request)
    {
        $districts = District::where('province', $request->province_id)->where('publishStatus', '1')->get();
        return response()->json([
            'districts' => $districts,
        ]);
    }

    public function getLocal(Request $request)
    {
        // dd($request->all());
        $locals = City::where('district_id', $request->district_id)->get();
        return response()->json([
            'locals' => $locals,
        ]);
    }

    public function comment(Request $request)
    {

        $customer = auth()->guard('customer')->user();
        if (!$customer) {
            $response = array(
                'error' => true,
                'msg' => 'Plz Login To Ask About Product !!'
            );
            return response()->json($response, 200);
        }
        $rules = array(
            'product_id' => 'required|exists:products,id',
            'question_answer' => 'required|string',
            'parent_id' => 'nullable|exists:question_answers,id',
            'status' => 'nullable|in:0,1'
        );
        $v = Validator::make($request->all(), $rules);
        if (!$v->passes()) {
            $messages = $v->messages();
            foreach ($rules as $key => $value) {
                $verrors[$key] = $messages->first($key);
            }
            $response_values = array(
                'validate' => true,
                'validation_failed' => 1,
                'errors' => $verrors
            );
            return response()->json($response_values, 200);
        }

        try {
            $product = Product::where('id', $request->product_id)->first();
            if (!$product) {
                throw new Exception('Something Went Wrong !!');
            }
            $data = $request->all();
            $data['customer_id'] = $customer->id;
            $data['seller_id'] = 1;
            // dd($data);
            $this->questionanswer->fill($data);
            $status = $this->questionanswer->save();

            $user = auth()->guard('customer')->user();
            if ($user) {
                $all_question = $this->questionanswer->where('product_id', $product->id)->where('status', 1)->whereNull('parent_id')->orderBy('id', 'DESC')->get();
                $user_specific_question = $this->questionanswer->where('product_id', $product->id)->whereNull('parent_id')->where('customer_id', $user->id)->where('status', 0)->orderBy('id', 'DESC')->get();
                $comment = $this->getCommentList($all_question, $user_specific_question);
            } else {
                $all_question = $this->questionanswer->where('product_id', $product->id)->where('status', 1)->whereNull('parent_id')->orderBy('id', 'DESC')->get();
                $comment = $this->getCommentList($all_question);
            }
            return view('frontend.customer.commentlist')
                ->with('questionAnswer', $comment);
        } catch (\Exception $ex) {
            $response = [
                'error' => true,
                'data' => null,
                'msg' => $ex->getMessage()
            ];
            return response()->json($response, 200);
        }
    }

    public function traceOrder(Request $request)
    {
        //    dd($request->all());
        $email = $request->email;
        $refId = $request->refId;
        $order_detail = Order::where('ref_id', $refId)->first();
        if (!$order_detail) {
            return redirect('/')->with('error', 'Order Not Found');
        }
        $user_detail = New_Customer::where('id', $order_detail->user_id)->first();
        if ($user_detail->id === 1) {
            $order = Order::where('email', $email)->where('ref_id', $refId)->first();
        } else {
            if ($user_detail->email != $email) {
                return redirect('/')->with('error', 'Order Not Found');
            }
            $order = Order::where('user_id', $user_detail->id)->where('ref_id', $refId)->first();
        }
        if (!isset($order)) {
            return redirect('/')->with('error', 'Order Not Found');
        }

        $order_status = OrderStatus::where('order_id', $order->id)->get();
        $order_status = collect($order_status)->map(function ($item) {
            return [
                'created_at' => $item->created_at,
                'status_value' => ($item->status == 1) ? 'SEEN' : (($item->status == 2) ? 'READY_TO_SHIP' : (($item->status == 3) ? 'DISPATCHED' : (($item->status == 4) ? 'SHIPED' : (($item->status == 5) ? 'DELIVERED' : (($item->status == 6) ? 'CANCELED' : 'REJECTED')))))
            ];
        });
        return view('frontend.traceOrder', compact('order', 'order_status'));
    }

    public function getProvince(Request $request)
    {
        $province = Province::where('id', $request->province_id)->where('publishStatus', 1)->first();

        if (!$province) {
            return response()->json([
                'error' => true,
                'data' => null,
                'msg' => 'Invalid Province Id !'
            ], 200);
        }
        $district = District::where('province', $province->id)->where('publishStatus', 1)->get();
        return response()->json([
            'error' => false,
            'data' => [
                'child' => $district
            ],
            'msg' => 'Success !'
        ], 200);
    }

    public function getDistrictData(Request $request)
    {
        $district = District::where('id', $request->district_id)->first();

        if (!$district) {
            return response()->json([
                'error' => true,
                'data' => null,
                'msg' => 'Invalid District Id !'
            ], 200);
        }
        $local = City::where('district_id', $district->id)->get();
        return response()->json([
            'error' => false,
            'data' => [
                'child' => $local
            ],
            'msg' => 'Success !'
        ], 200);
    }

    public function getAreaData(request $request)
    {
        $local_area = City::where('id', $request->area_id)->first();
        $location = $local_area->getRouteCharge;
        $main_data = [];
        foreach ($location as $key => $l) {
            $main_data[$key]['id'] = $l->id;
            $main_data[$key]['local_id'] = $l->local_id;
            $main_data[$key]['title'] = $l->title;
            $main_data[$key]['slug'] = $l->slug;
            $main_data[$key]['image'] = $l->image;
            $main_data[$key]['charge'] = $l->deliveryRoute->charge;
        }
        $response = [
            'error' => false,
            'data' => [
                'child' => $main_data
            ],
            'msg' => 'Area With Charge'
        ];
        return response()->json($response, 200);
    }

    public function getAreaDataCustomer(request $request)
    {
        // dd($request->all());
        $local_area = City::where('city_name', $request->area_id)->orWhere('id', $request->area_id)->first();
        $location = $local_area->getRouteCharge;
        $main_data = [];
        if ($location) {
            foreach ($location as $key => $l) {
                $main_data[$key]['id'] = $l->id;
                $main_data[$key]['local_id'] = $l->local_id;
                $main_data[$key]['title'] = $l->title;
                $main_data[$key]['slug'] = $l->slug;
                $main_data[$key]['image'] = $l->image;
                $main_data[$key]['charge'] = $l->deliveryRoute->charge;
            }
        }

        $response = [
            'error' => false,
            'data' => [
                'child' => $main_data
            ],
            'msg' => 'Area With Charge'
        ];
        return response()->json($response, 200);
    }


    public function updateBillingAddressNew(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required|max:15',
            'email' => 'required',
            'province' => 'required',
            'state' => 'required',
            'area' => 'required',
            'country'=>'required|string'
            // 'additional_address' => 'required',
        ]);


        $area = City::where('id', $request->area)->first();

        // $location = Location::where('id', $request->additional_address)->first();
        $data = $request->except(['same', 'payment', 'province', 'district', 'area']);
        $data['province'] = $request->province;
        $data['state'] = $request->state;

        $data['area'] = $request->area;
        $data['country'] = $request->country;
        // $data['additional_address'] = $location->title ?? null;
        // $data['area_id'] = $location->id ?? null;



        DB::beginTransaction();
        try {
            $previous_user_in_billing = UserBillingAddress::findOrFail($id);
            LogEvent::dispatch('BillingAddress Updated', 'BillingAddress Updated', route('updateBillingAddress', $id));
            $previous_user_in_billing->update($data);
            DB::commit();
            return redirect()->back()->with('success', 'Address updated successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            $request->session()->flash('error', 'OOPs, Please Try Again.');
            return back();
        }
    }

    public function setDefaultAddress(Request $request)
    {
        $province = Province::where('id', $request->province)->first();
        $district = District::where('id', $request->district)->first();
        // dd($district);
        request()->session()->forget('detail_info_address');

        $data = $request->all();

        $area = City::where('id', $request->area)->first();

        $charge_array = [];
        $min_charge = 0;
        $location_detail = $area->getRouteCharge;
        if ($location_detail->count() <= 0) {
            $response = [
                'error' => true,
                'msg' => 'Something Went Wrong !!'
            ];
            return response()->json($response, 200);
        }
        if (!empty($location_detail)) {
            foreach ($location_detail as $key => $l) {
                $new = $l->deliveryRoute;
                if ($key == 0) {
                    $min_charge = $new->charge;
                    $charge_array['location_id'] = $new->location_id;
                    $charge_array['charge'] = $new->charge;
                } else {
                    if ($min_charge >    $new->charge) {
                        $min_charge = $new->charge;
                        $charge_array['location_id'] = $new->location_id;
                        $charge_array['charge'] = $new->charge;
                    }
                }
            }
        }

        $location = Location::whereId($charge_array['location_id'])->first();

        $detail_info_address = [
            'province' => $province->eng_name,
            'district' => $district->np_name,
            'area' => $area->city_name,
            'min_charge' => $min_charge
        ];

        request()->session()->put('detail_info_address', $detail_info_address);

        $address_html = "<span>" . $request->province . ',' . $request->district . ',' . $area->city_name . "</span>";
        $charge_html = "<b class='text-danger'>$." . $min_charge . "</b>";

        $response = [
            'province' => $request->province,
            'district' => $request->district,
            'area' => $area->city_name,
            'min_charge' => $min_charge,
            'address_html' => $address_html,
            'charge' => $charge_html
        ];

        request()->session()->put('detail_info_address', $detail_info_address);

        $address_html = "<span>" . $province->eng_name . ',' . $district->np_name . ',' . $area->city_name . "</span>";
        $charge_html = "<b class='text-danger'>$." . $min_charge . "</b>";

        $response = [
            'province' => $request->province,
            'district' => $request->district,
            'area' => $area->city_name,
            'min_charge' => $min_charge,
            'address_html' => $address_html,
            'charge' => $charge_html
        ];

        return response()->json($response, 200);
    }

    public function orderPdf(Request $request, $refid)
    {

        $order = Order::where('ref_id', $refid)->first();
        $customer = New_Customer::findOrFail($order->user_id);
        $refId = $order->ref_id;
        $data = $order->orderAssets;
        $path = public_path('/backend/dist/img/logo.png');
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $img = file_get_contents($path);
        $tick = 'data:image/' . $type . ';base64,' . base64_encode($img);
        $pdf = PDF::setOptions(['defaultFont' => 'sans-serif'])->loadView('frontend.orderpdf', compact('order', 'data', 'refId', 'customer', 'tick'));

        return $pdf->download('MyStore.pdf');
    }

    public function orderPdfall(Request $request, $refid)
    {
        $order = Order::where('ref_id', $refid)->first();
        $customer = New_Customer::findOrFail($order->user_id);
        $refId = $order->ref_id;
        $data = $order->orderAssets;
        $path = public_path('/backend/dist/img/logo.png');
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $img = file_get_contents($path);
        $tick = 'data:image/' . $type . ';base64,' . base64_encode($img);
        $pdf = PDF::setOptions(['defaultFont' => 'sans-serif'])->loadView('frontend.orderpdfall', compact('order', 'data', 'refId', 'customer', 'tick'));

        return $pdf->download('MyStore.pdf');
    }

    public function mobileOrderPdf(Request $request, $refid)
    {

        $order = Order::where('ref_id', $refid)->first();
        $customer = New_Customer::findOrFail($order->user_id);
        $refId = $order->ref_id;
        $data = $order->orderAssets;

        $path = public_path('/backend/dist/img/logo.png');
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $img = file_get_contents($path);
        $tick = 'data:image/' . $type . ';base64,' . base64_encode($img);

        $pdf = PDF::setOptions(['defaultFont' => 'sans-serif'])->loadView('frontend.orderpdf', compact('order', 'data', 'refId', 'customer', 'tick'));
        return $pdf->download('MyStore.pdf');
    }

    public function mobileOrderPdf1(Request $request, $refid)
    {
        $setting = Setting::first();
        $imagePath = $setting->value ?? public_path('/backend/dist/img/logo.png');
        $order = Order::where('ref_id', $refid)->first();
        $customer = New_Customer::findOrFail($order->user_id);
        $refId = $order->ref_id;
        $data = $order->orderAssets;

        $path = $imagePath;
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $img = file_get_contents($path);
        $tick = 'data:image/' . $type . ';base64,' . base64_encode($img);
        // return view('frontend.orderpdf', compact('order', 'data', 'refId', 'customer', 'tick'));
        $pdf = PDF::setOptions(['defaultFont' => 'sans-serif'])->loadView('frontend.orderpdf', compact('order', 'data', 'refId', 'customer', 'tick'));
        // $pdf->save(public_path('mobilepdf/' . $order->id.'.pdf'));
        return $pdf->download('MyStore.pdf');
    }

    public function autoSearchTag(Request $request)
    {
        $data = [];
        $products = Product::where('status', '1')->where('publishStatus', '1')->get();
        foreach ($products as $key => $item) {
            $data[] = $item->name;
        }
        return $data;
    }

    public function autoSearchTagAdmin(Request $request)
    {
        $data = [];
        $products = Product::whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($request->search_query) . '%'])
            ->where('status', '1')
            ->where('publishStatus', '1')
            ->get();
        $data = collect($products)->pluck('name')->toArray();
        return $data;
    }

    public function brandIndex(Request $request, $slug)
    {

        $brand = Brand::where('slug', $slug)->firstOrFail();
        // dd($brand);
        $products = product::where('brand_id', $brand->id)->where('status', 1)->where('publishStatus', 1)->get();


        $color_id = [];
        $brand_id = [];
        foreach ($products as $product) {
            if ($product->brand_id != null) {
                $brand_id[] = $product->brand_id;
            }
            foreach ($product->stocks as $stock) {
                $color_id[] = $stock->color_id;
            }
        }
        $brand_id = collect($brand_id)->unique();
        $color_id = collect($color_id)->unique();
        $brands = Brand::whereIn('id', $brand_id)->get();
        $colors = Color::whereIn('id', $color_id)->get();

        LogEvent::dispatch('Brand View', 'Brand View', route('brand.index', $slug));

        return view('frontend.brand.index', compact('products', 'colors', 'brands', 'slug'));
    }

    // public function testingKhaltiData()
    // {
    //     $pid="test";
    //     $postRequest='{
    //         "return_url": "https://example.com/payment/",
    //         "website_url": "https://example.com/",
    //         "amount": 1300,
    //         "purchase_order_id": "test12",
    //         "purchase_order_name": "test",
    //         "customer_info": {
    //             "name": "Ashim Upadhaya",
    //             "email": "example@gmail.com",
    //             "phone": "9811496763"
    //         },
    //         "amount_breakdown": [
    //             {
    //                 "label": "Mark Price",
    //                 "amount": 1000
    //             },
    //             {
    //                 "label": "VAT",
    //                 "amount": 300
    //             }
    //         ],
    //         "product_details": [
    //             {
    //                 "identity": "1234567890111",
    //                 "name": "Khalti logo",
    //                 "total_price": 1300,
    //                 "quantity": 1,
    //          "unit_price": 1300
    //             }
    //         ]
    //       }';

    //       $post=json_decode($postRequest);
    //         $cURLConnection=curl_init('https://khalti.com/api/v2/epayment/initiate/');
    //         curl_setopt($cURLConnection, CURLOPT_ENCODING,true);
    //         // curl_setopt($ch, CURLOPT_POST, 1);
    //         curl_setopt($cURLConnection, CURLOPT_POSTFIELDS, $postRequest);
    //         curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER,true);
    //         curl_setopt($cURLConnection, CURLOPT_HTTPHEADER, array(
    //             'Content-Type:application/json',
    //             'Authorization:live_secret_key_28c3653d1899474790859e2dc02a67a6'
    //         ));

    //         $apiResponse=curl_exec($cURLConnection);
    //         curl_close($cURLConnection);
    //         $jsonResponse=json_decode($apiResponse);
    //         echo $apiResponse;

    //       dd($apiResponse);
    // }

    public function getKhaltiResponse(Request $request)
    {
        $args = http_build_query(array(
            "pidx" => $request->pidx
        ));
        $url = "https://khalti.com/api/v2/epayment/lookup/";
        # Make the call using API.
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $args);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $headers = ['Authorization: Key live_secret_key_28c3653d1899474790859e2dc02a67a6'];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($status == 200) {
            $response = [
                'error' => false,
                'msg' => 'Order Created Successfully !!'
            ];
        } else {
            $response = [
                'error' => true,
                'msg' => 'Sorry !! Something Went Wrong'
            ];
        }
        return response()->json($response, 200);
    }

    public function mailTrack(Request $request, $refId, $email)
    {
        $email = $request->email;
        $refId = $request->refId;
        $order_detail = Order::where('ref_id', $refId)->first();
        if (!$order_detail) {
            return redirect('/')->with('error', 'Order Not Found');
        }
        $user_detail = New_Customer::where('id', $order_detail->user_id)->first();
        if ($user_detail->id === 1) {
            $order = Order::where('email', $email)->where('ref_id', $refId)->first();
        } else {
            if ($user_detail->email != $email) {
                return redirect('/')->with('error', 'Order Not Found');
            }
            $order = Order::where('user_id', $user_detail->id)->where('ref_id', $refId)->first();
        }
        if (!isset($order)) {
            return redirect('/')->with('error', 'Order Not Found');
        }

        $order_status = OrderStatus::where('order_id', $order->id)->get();
        $order_status = collect($order_status)->map(function ($item) {
            return [
                'created_at' => $item->created_at,
                'status_value' => ($item->status == 1) ? 'SEEN' : (($item->status == 2) ? 'READY_TO_SHIP' : (($item->status == 3) ? 'DISPATCHED' : (($item->status == 4) ? 'SHIPED' : (($item->status == 5) ? 'DELIVERED' : (($item->status == 6) ? 'CANCELED' : 'REJECTED')))))
            ];
        });
        return view('frontend.traceOrder', compact('order', 'order_status'));
    }

    public function updateLocation(Request $request)
    {
        try {
            $request->validate([
                'city' => 'required|string'
            ]);

            $city = $request->city;
            Session::forget('city');
            Session::put('city', $city);
            return redirect()->back();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred. Please try again later.');
        }
    }

    public function sendInquiry(Request $request)
    {
        $validate = $request->validate([
            'full_name' => 'required',
            'email' => 'required|email',
            'contact' => 'required',
            'title' => 'required',
            'message' => 'required',
            'g-recaptcha-response' => 'nullable'
        ]);

        $data = $request->except('message');
        $data['message'] = strip_tags($request->message);
        Inquiry::create($data);

        // InquiryEmailJob::dispatch($data);
        $setting = \App\Models\Setting::where('key','email')->first();
        // dd($setting);
        Mail::to($setting->value)->send(new InquiryMail($data));
        return back()->with('success', 'Your inquiry has been submitted successfully. We will get back to you soon');
    }

    public function sendgetinquiry(Request $request)
    {
        
        $validate = $request->validate([
            'full_name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'message' => 'required',
            'g-recaptcha-response' => 'nullable',
            'product_id'=>'required|exists:products,id'
        ]);
        DB::beginTransaction();
        try{
            $data=$request->all();
            $getInquiry=GetInquiry::create($data);
            $setting = \App\Models\Setting::where('key','email')->first();
            Mail::to($setting->value)->send(new GetInquiryMail($getInquiry));
            DB::commit();
            return back()->with('success', 'Your inquiry has been submitted successfully. We will get back to you soon');
        }catch(\Throwable $th){
            DB::rollBack();
            return back()->with('error', 'Something Went Wrong !!');
        }
    }

    public function customerInquiryForm(Request $request,$slug){
        $product=Product::where('slug',$slug)->first();
        if(!$product){
            $request->session()->flash('error','Something Went Wrong !!');
            return redirect()->back();
        }
        return view('frontend.inquiry',compact('product'));
    }
}
