<?php

namespace App\Http\Controllers\Api;

use App\Models\City;
use App\Models\Brand;
use App\Models\Color;
use App\Models\Local;
use App\Models\Slider;
use App\Models\Product;
use App\Models\Category;
use App\Models\Countrey;
use App\Models\Location;
use App\Models\WishList;
use App\Models\ProductCity;
use App\Models\New_Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\OrderTimeSetting;
use App\Models\ProductAttribute;
use App\Helpers\ProductFormHelper;
use App\Http\Controllers\Controller;
use App\Models\AdvertisementPosition;
use Illuminate\Support\Facades\Session;
use App\Actions\Product\ProductFormAction;
use App\Models\Admin\Product\Featured\FeaturedSection;

class HomeController extends Controller
{
    protected $featuredsection = null;
    public function __construct(FeaturedSection $featuredsection)
    {
        $this->featuredsection = $featuredsection;
    }

   

    public function getRegisterCountryList(Request $request){
        $countryList=Countrey::get();
        $response=[
            'error'=>false,
            'data'=>$countryList,
            'msg'=>'Country With Flag List !!'
        ];
        return response()->json($response,200);
    }
    public function updateLocation(Request $request)
    {
        $city = $request->city;
        Session::forget('city');
        Session::put('city', $city);
        return response()->json(['status'=>200,'city'=>Session::get('city')]);
    }

    public function todaysOrder()
    {
        $today = Carbon::now()->format('l');
        $order_timing_check = OrderTimeSetting::where('day', $today)->exists();
        if($order_timing_check)
        {
            $todays_order_time = OrderTimeSetting::where('day',$today)->first();
            if($todays_order_time->day_off == 0)
            {
                return response()->json(['status'=>200, 'data'=>$todays_order_time,'message'=>'Can place order today']);
            }else{
                return response()->json(['status'=>200, 'data'=>$todays_order_time, 'message'=>'Day off']);
            }
        }

        return response()->json(['status'=>200, 'message'=>'Invalid Day']);
    }

    public function getProductsByCity(Request $request)
    {
        $city = $request->query('city', 'Kathmandu'); // Default to 'Kathmandu' if city is not provided in the query parameters
        // $product = Product::select(['id','name','slug','rating'])
        // ->where('status', 1)
        // ->where('publishStatus', '1')
        // ->whereHas('city', function ($q) use ($city) {
        //     $q->where('cities.city_name', $city); //cityName in production
        // })
        // ->get();
        // return response()->json([
        //     'status' => 200, 
        //     'city' => $city,
        //     'product' => $product
        // ]);
        try{
            if ($request->user_id) {
                
                $user = New_Customer::where('id', $request->user_id)->first();
                $all_product = Product::select(['id','name','slug','rating'])
                            ->where('status', 1)
                            ->where('publishStatus', '1')
                            ->whereHas('city', function ($q) use ($city) {
                                $q->where('cities.city_name', $city); //cityName in production
                            })
                            ->get();
    
                // $all_product = Product::select(['id', 'name', 'slug', 'rating'])->get();
    
                $product = Product::select(['id','name','slug','rating'])
                        ->where('status', 1)
                        ->where('publishStatus', '1')
                        ->whereHas('city', function ($q) use ($city) {
                            $q->where('cities.city_name', $city); //cityName in production
                        })
                        ->get();
                // $product = Product::select(['id', 'name', 'slug', 'rating'])->get();
                foreach ($product as $new) {
                    $new->setAttribute('special_price', null);
                }
    
                foreach ($all_product as $key => $p) {
                    $first = $p->getPrice;
                    $image = $p->images;
    
                    $product[$key]->setAttribute('price', $first->price);
                    $product[$key]->setAttribute('special_price', $first->special_price);
                    $product[$key]->setAttribute('image', $image[0]['image']);
                }
                foreach ($product as $data) {
                    $data->setAttribute('is_wish', false);
                    $wish = WishList::where('product_id', $data->id)->where('user_id', $user->id)->first();
                    if ($wish) {
                        $data->setAttribute('is_wish', true);
                    }
                }
                $all_featured_product = $this->featuredsection->with('product')->where('publishStatus', '1')->select(['id', 'title'])->get();
                $featured_product = $this->featuredsection->where('publishStatus', '1')->select(['id', 'title'])->with('product')->get();
                foreach ($featured_product as $v) {
                    foreach ($v->product as $d) {
                        $d->setAttribute('special_price', null);
                    }
                }
                foreach ($all_featured_product as $value => $pp) {
                    foreach ($pp->product as $key => $nn) {
                        $f_first = $nn->getPrice;
                        $f_image = $nn->images;
                        $featured_product[$value]->product[$key]->setAttribute('price', $f_first->price);
                        $featured_product[$value]->product[$key]->setAttribute('special_price', $f_first->special_price);
                        $featured_product[$value]->product[$key]->setAttribute('image', $f_image[0]['image']);
                        $featured_product[$value]->product[$key]->setAttribute('varient_id', $nn->stocks[0]->id);
                    }
                }
                foreach ($featured_product as $datas) {
                    foreach ($datas->product as $new_data) {
                        $new_data->setAttribute('is_wish', false);
                        $wish = WishList::where('product_id', $new_data->id)->where('user_id', $user->id)->first();
                        if ($wish) {
                            $new_data->setAttribute('is_wish', true);
                        }
                    }
                }
            } else {
                $all_product = Product::select(['id','name','slug','rating'])
                            ->where('status', 1)
                            ->where('publishStatus', '1')
                            ->whereHas('city', function ($q) use ($city) {
                                $q->where('cities.city_name', $city); //cityName in production
                            })
                            ->get();
    
                // $all_product = Product::select(['id', 'name', 'slug', 'rating'])->get();
    
                $product = Product::select(['id','name','slug','rating'])
                        ->where('status', 1)
                        ->where('publishStatus', '1')
                        ->whereHas('city', function ($q) use ($city) {
                            $q->where('cities.city_name', $city); //cityName in production
                        })
                        ->get();
                // $product = Product::select(['id', 'name', 'slug', 'rating'])->get();
                foreach ($product as $new) {
                    $new->setAttribute('special_price', null);
                }
                foreach ($all_product as $key => $p) {
                    $first = $p->getPrice;
                    $image = $p->images;
    
                    $product[$key]->setAttribute('price', $first->price);
                    $product[$key]->setAttribute('special_price', $first->special_price);
                    if(count($image) >0)
                    {
                        $product[$key]->setAttribute('image', $image[0]['image']);
                    }
                    else
                    {
                        $product[$key]->setAttribute('image', 'null');
                    }
                    
                   
                }
                foreach ($product as $data) {
                    $data->setAttribute('is_wish', false);
                }
                $all_featured_product = $this->featuredsection->where('publishStatus', '1')->select(['id', 'title'])->with('product')->get();
                $featured_product = $this->featuredsection->where('publishStatus', '1')->select(['id', 'title'])->with('product')->get();
                foreach ($featured_product as $v) {
                    foreach ($v->product as $d) {
                        $d->setAttribute('special_price', null);
                    }
                }
                foreach ($all_featured_product as $value => $pp) {
                    foreach ($pp->product as $key => $nn) {
                        $f_first = $nn->getPrice;
                        $f_image = $nn->images;
                        $featured_product[$value]->product[$key]->setAttribute('price', $f_first->price);
                        if(getOfferProduct($nn,$nn->stocks[0]) !=null)
                        {
                            $featured_product[$value]->product[$key]->setAttribute('special_price', getOfferProduct($nn,$nn->stocks[0]));
                        }
                        else
                        {
                            $featured_product[$value]->product[$key]->setAttribute('special_price', $f_first->special_price);
                        }
                        $featured_product[$value]->product[$key]->setAttribute('image', $f_image[0]['image']);
                        $featured_product[$value]->product[$key]->setAttribute('is_wish', false);
                        $featured_product[$value]->product[$key]->setAttribute('varient_id', $nn->stocks[0]->id);
                    }
                }
            }
            $response = [
                'error' => false,
                'data' => [
                    'featured_section' => $featured_product,
                    'product' => $product,
                    'all_product' => $all_product
                ],
                'msg' => 'Home Page Data'
            ];
            return response()->json($response, 200);
        }catch(\Exception $e){
            $response = [
                'error' => true,
                'data' => null,
                'msg' => $e->getMessage()
            ];
            return response()->json($response, 200);
        }


    }

    public function index(Request $request)
    {   
        try{
            if(Session::get('city'))
            {
                $sessionCity = Session::get('city');
                $currentCity = City::where('city_name','LIKE','%'.$sessionCity.'%')->first();
            }else{
            //Fetch city products
            $ipAddress = $request->ip();
            $geoData = Location::get($ipAddress);
            // $currentCity = Local::first(); //for development
            $geoCity = $geoData->cityName;
            $currentCity = City::where('city_name','LIKE','%'.$geoCity.'%')->first();
            }

            
            $slider = Slider::where('publish_status', '1')->select(['id', 'title', 'body', 'image','alt_img','link'])->where('show_mob',1)->limit(4)->get();
            $add_position = AdvertisementPosition::orderBy('position_id', 'ASC')->limit(4)->get();  
            $all_add = [];
            foreach ($add_position as $ads) {
                if($ads->ads !=null)
                {
                    array_push($all_add, $ads->ads);
                }
                
            }
            // -------------------------------Product List--------------------------
            if ($request->user_id) {
                
                $user = New_Customer::where('id', $request->user_id)->first();
                $all_product = Product::select(['id','name','slug','rating'])
                            ->where('status', 1)
                            ->where('publishStatus', '1')
                            ->whereHas('city', function ($q) use ($currentCity) {
                                $q->where('cities.city_name', $currentCity->city_name); //cityName in production
                            })
                            ->get();
    
                // $all_product = Product::select(['id', 'name', 'slug', 'rating'])->get();

                $product = Product::select(['id','name','slug','rating'])
                        ->where('status', 1)
                        ->where('publishStatus', '1')
                        ->whereHas('city', function ($q) use ($currentCity) {
                            $q->where('cities.city_name', $currentCity->city_name); //cityName in production
                        })
                        ->get();
                // $product = Product::select(['id', 'name', 'slug', 'rating'])->get();
                foreach ($product as $new) {
                    $new->setAttribute('special_price', null);
                }

                foreach ($all_product as $key => $p) {
                    $first = $p->getPrice;
                    $image = $p->images;

                    $product[$key]->setAttribute('price', $first->price);
                    $product[$key]->setAttribute('special_price', $first->special_price);
                    $product[$key]->setAttribute('image', $image[0]['image']);
                }
                foreach ($product as $data) {
                    $data->setAttribute('is_wish', false);
                    $wish = WishList::where('product_id', $data->id)->where('user_id', $user->id)->first();
                    if ($wish) {
                        $data->setAttribute('is_wish', true);
                    }
                }
                $all_featured_product = $this->featuredsection->with('product')->where('publishStatus', '1')->select(['id', 'title'])->get();
                $featured_product = $this->featuredsection->where('publishStatus', '1')->select(['id', 'title'])->with('product')->get();
                foreach ($featured_product as $v) {
                    foreach ($v->product as $d) {
                        $d->setAttribute('special_price', null);
                    }
                }
                foreach ($all_featured_product as $value => $pp) {
                    foreach ($pp->product as $key => $nn) {
                        $f_first = $nn->getPrice;
                        $f_image = $nn->images;
                        $featured_product[$value]->product[$key]->setAttribute('price', $f_first->price);
                        $featured_product[$value]->product[$key]->setAttribute('special_price', $f_first->special_price);
                        $featured_product[$value]->product[$key]->setAttribute('image', $f_image[0]['image']);
                        $featured_product[$value]->product[$key]->setAttribute('varient_id', $nn->stocks[0]->id);
                    }
                }
                foreach ($featured_product as $datas) {
                    foreach ($datas->product as $new_data) {
                        $new_data->setAttribute('is_wish', false);
                        $wish = WishList::where('product_id', $new_data->id)->where('user_id', $user->id)->first();
                        if ($wish) {
                            $new_data->setAttribute('is_wish', true);
                        }
                    }
                }
            } else {
                $all_product = Product::select(['id','name','slug','rating'])
                            ->where('status', 1)
                            ->where('publishStatus', '1')
                            ->whereHas('city', function ($q) use ($currentCity) {
                                $q->where('cities.city_name', $currentCity->city_name); //cityName in production
                            })
                            ->get();
    
                // $all_product = Product::select(['id', 'name', 'slug', 'rating'])->get();

                $product = Product::select(['id','name','slug','rating'])
                        ->where('status', 1)
                        ->where('publishStatus', '1')
                        ->whereHas('city', function ($q) use ($currentCity) {
                            $q->where('cities.city_name', $currentCity->city_name); //cityName in production
                        })
                        ->get();
                // $product = Product::select(['id', 'name', 'slug', 'rating'])->get();
                foreach ($product as $new) {
                    $new->setAttribute('special_price', null);
                }
                foreach ($all_product as $key => $p) {
                    $first = $p->getPrice;
                    $image = $p->images;

                    $product[$key]->setAttribute('price', $first->price);
                    $product[$key]->setAttribute('special_price', $first->special_price);
                    if(count($image) >0)
                    {
                        $product[$key]->setAttribute('image', $image[0]['image']);
                    }
                    else
                    {
                        $product[$key]->setAttribute('image', 'null');
                    }
                    
                   
                }
                foreach ($product as $data) {
                    $data->setAttribute('is_wish', false);
                }
                $all_featured_product = $this->featuredsection->where('publishStatus', '1')->select(['id', 'title'])->with('product')->get();
                $featured_product = $this->featuredsection->where('publishStatus', '1')->select(['id', 'title'])->with('product')->get();
                foreach ($featured_product as $v) {
                    foreach ($v->product as $d) {
                        $d->setAttribute('special_price', null);
                    }
                }
                foreach ($all_featured_product as $value => $pp) {
                    foreach ($pp->product as $key => $nn) {
                        $f_first = $nn->getPrice;
                        $f_image = $nn->images;
                        $featured_product[$value]->product[$key]->setAttribute('price', $f_first->price);
                        if(getOfferProduct($nn,$nn->stocks[0]) !=null)
                        {
                            $featured_product[$value]->product[$key]->setAttribute('special_price', getOfferProduct($nn,$nn->stocks[0]));
                        }
                        else
                        {
                            $featured_product[$value]->product[$key]->setAttribute('special_price', $f_first->special_price);
                        }
                        $featured_product[$value]->product[$key]->setAttribute('image', $f_image[0]['image']);
                        $featured_product[$value]->product[$key]->setAttribute('is_wish', false);
                        $featured_product[$value]->product[$key]->setAttribute('varient_id', $nn->stocks[0]->id);
                    }
                }
            }


            // -------------------------------/Product List--------------------------
            $featured_category = Category::where('parent_id', null)->where('showOnHome', '1')->select(['id', 'title', 'slug', 'image'])->get();

            foreach ($featured_product as $fe_product) {
                foreach ($fe_product->product as $pro) {
                    $pro->makehidden('pivot');
                }
            }
            $response = [
                'error' => false,
                'data' => [
                    'geoData' => $geoData,
                    'city' => $currentCity,
                    'sliders' => $slider,
                    'advertisements' => $all_add,
                    'featured_section' => $featured_product,
                    'featured_category' => $featured_category,
                    'product' => $product,
                    'all_product' => $all_product
                ],
                'msg' => 'Home Page Data'
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

    public function attribute()
    {
        try{
            $brands = Brand::select(['id', 'name', 'logo'])->get();

            $colors = Color::select(['id', 'title', 'colorCode'])->get();
    
            $response = [
                'error' => false,
                'data' => [
                    'brands' => $brands,
                    'colors' => $colors
                ],
                'msg' => 'List Of Attribute !!'
            ];
    
            return response()->json($response, 200);
        }catch(\Exception $ex){
            $response = [
                'error' => true,
                'data' =>null,
                'msg' =>$ex->getMessage()
            ];
    
            return response()->json($response, 200);
        }
    }

    public function getAttributeBrandProduct(Request $request, $id)
    {

        try{
            $brand = Brand::where('id', $id)->first();
            $products = $brand->getProduct;
    
    
            $data = [];
            foreach ($products as $key => $product) {
                $data[$key]['id'] = $product->id;
                $data[$key]['name'] = $product->name;
                $data[$key]['slug'] = $product->slug;
                $data[$key]['short_description'] = $product->short_description;
                $data[$key]['long_description'] = $product->long_description;
                $data[$key]['brand_id'] = $product->brand_id;
                $data[$key]['rating'] = $product->rating;
                $data[$key]['price'] = $product->getPrice->price;
                if ($product->getPrice->special_price != null) {
                    $data[$key]['special_price'] = $product->getPrice->special_price;
                } else {
                    $data[$key]['special_price'] = null;
                }
                $data[$key]['varient_id']=$product->stocks[0]->id;
                $data[$key]['image'] = $product->images[0]['image'];
            }
    
            $response = [
                'error' => false,
                'data' => $data,
                'msg' => 'Brand Products'
            ];
            return response()->json($response, 200);
        }catch(\Exception $ex){
            $response = [
                'error' => true,
                'data' =>null,
                'msg' =>$ex->getMessage()
            ];
    
            return response()->json($response, 200);
        }

       
    }

    public function getAttributeColorProduct(Request $request, $id)
    {
        try{
            $color = Color::where('id', $id)->first();
            $color_products = $color->getColorProduct;
            $data = [];
            foreach ($color_products as $key => $color) {
                $product = $color->product;
                $data[$key]['id'] = $product->id;
                $data[$key]['name'] = $product->name;
                $data[$key]['slug'] = $product->slug;
                $data[$key]['short_description'] = $product->short_description;
                $data[$key]['long_description'] = $product->long_description;
                $data[$key]['brand_id'] = $product->brand_id;
                $data[$key]['rating'] = $product->rating;
                $data[$key]['price'] = $product->getPrice->price;
                if ($product->getPrice->special_price != null) {
                    $data[$key]['special_price'] = $product->getPrice->special_price;
                } else {
                    $data[$key]['special_price'] = null;
                }
                $data[$key]['image'] = $product->images[0]['image'];
            }
    
    
    
            $response = [
                'error' => false,
                'data' => $data,
                'msg' => 'Colors Products'
            ];
            return response()->json($response, 200);
        }catch(\Exception $ex){
            $response = [
                'error' => true,
                'data' =>null,
                'msg' =>$ex->getMessage()
            ];
    
            return response()->json($response, 200);
        }
    }
}
