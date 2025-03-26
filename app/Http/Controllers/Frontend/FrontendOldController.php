<?php

namespace App\Http\Controllers\Frontend;

use App\Data\Product\ProductDetailData;
use App\Models\{
    Tag,
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
    Province,
    Product,
    Order,
    OrderAsset,
    OrderStock,
    ProductStock,
    UserShippingAddress,
    WishList,
    Menu,
    New_Customer
};


use App\Mail\OrderConfirm;
use App\Models\Category;
use App\Models\District;
use App\Models\DeliveryRoute;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\QuestionAnswer;
use App\Models\UserBillingAddress;
use App\Http\Controllers\Controller;
use App\Mail\OrderConfirm as MailOrderConfirm;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Admin\Product\Featured\FeaturedSection;
use Symfony\Component\Console\Question\Question;

class FrontendOldController extends Controller
{
    //
    protected $folderName = 'frontend.';
    public function index()
    {
        $products = Product::with('images','category','features','brand','colors','sizes','stocks','stockways','productTags','attributes','dangerousGoods')->get();
        $top_sells = Product::with('images','category','features','brand','colors','sizes','stocks','stockways','productTags','attributes','dangerousGoods')->orderBy('total_sell','DESC')->take(2)->get();
        $heavy_discounted_product = [];        
        foreach($products as $product){   
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
                array_push($heavy_discounted_product, $product);
            }    
        }
        $heavy_discounted_product = array_slice($heavy_discounted_product,0,2);
        // dd($heavy_discounted_product);
        $new_arrivals = Product::with('images','category','features','brand','colors','sizes','stocks','stockways','productTags','attributes','dangerousGoods')->orderBy('created_at','DESC')->take(2)->get();
        $sliders = Slider::where('publish_status','=','1')->get();       
        $countries = Country::where('status','=','1')->get();
        $total_RFQ = RFQ::count();
        $homeCategory = Category::where(['parent_id'=>NULL,'showOnHome'=>'1'])->withCount(['ancestors', 'descendants'])->take(16)->with('children')->get();
        $product_features = FeaturedSection::where('publishStatus',true)->get();
        $recent_view_product_id = Session::get('pro_ids');
        if(isset($recent_view_product_id)){
            $recent_view_product_id = array_unique($recent_view_product_id);
        }else{
            $recent_view_product_id = [];
        }

        $recommended_products = Product::inRandomOrder()->limit(18)->get();
        $recent_view_products = Product::with('images','category','features','brand','colors','sizes','stocks','stockways','productTags','attributes','dangerousGoods')->whereIn('id',$recent_view_product_id)->get();
        return view($this->folderName . 'index', compact('top_sells','new_arrivals','heavy_discounted_product','total_RFQ','homeCategory','recommended_products','product_features','sliders','products','recent_view_products','countries'));
    }

    public function childrenCategory($id)
    {
        $category = Category::find($id);
        $children = Category::orWhereDescendantOf($category)->withDepth->get();
        return response()->json($children);
    }

    public function getDetails($slug)
    {
        $data = (new ProductDetailData())->getDetails($slug);
        $product = Product::where('slug', $slug)->first();
        $final_data = $this->getAllAttributePrice($product);
        // dd($final_data);
        $data['final_data']=$final_data;
        return view('frontend.product.details', $data);
    }
    public function getAllAttributePrice($product)
    {
        $keys = [];
        $new_stocks = collect($product->stockways)->groupBy('stock_id')->toArray();
        $data = [];
        $keys = [];
        $values = [];
        $colors = [];
        $color = "";
        $price = 0;
        $first_available = [];



        $avaibility = [];
        foreach($new_stocks as $index=>$stock){
            if(array_key_first($new_stocks) == $index){
                $db_stock = ProductStock::find($index);
                $price = $db_stock->price;
                $color = ($db_stock) ? $db_stock->againColor->title : "Null";
            }
            $db_stock = ProductStock::find($index);
            $color = $db_stock->againColor->title;
            $colors[$index] = $color;
            foreach($stock as $index1=>$final_stock){
                $categoryAttribute = CategoryAttribute::find($final_stock['key']);
                $data[$index][$final_stock['key']]=[
                    'category_title'=>($categoryAttribute) ? $categoryAttribute->title : "Null",
                    'value'=>$final_stock['value'],
                ];
                $keys[$final_stock['key']] = [
                    'id'=>$final_stock['key'],
                    'category_title'=>($categoryAttribute) ? $categoryAttribute->title : "Null",
                ];
                $values[$final_stock['key']][] = $new_stocks[$index][$index1]['value'];
                if(array_key_first($new_stocks) == $index){
                    $first_available[$final_stock['key']] = $final_stock;
                }
            }
        }

        $keys = collect($keys)->unique()->toArray();
        $final_data = [
            'keys'=>$keys,
            'values'=>collect($values)->sort()->toArray(),
            'first_available'=>$first_available,
            'colors'=>collect($colors)->sort()->toArray(),
            'color'=>$color,
            'price'=>$price,
        ];
        return $final_data;
        dd($first_available);
        dd($keys);
        dd($values);
    }
    public function getColors($row)
    {
        $colors = [];
        foreach($row as $r){
            $stock = ProductStock::find($r['stock_id']);
            $color = $stock->againColor->title;
            $colors[$stock['id']] = $color;
        }
       return $colors;
    }
    public function category_show($slug){
        if($slug == 'new'){ 

            $result = Product::with('images','category','features','brand','colors','sizes','stocks','stockways','productTags','attributes','dangerousGoods')->orderBy('created_at','DESC')->get();
            $products = [];
            foreach($result as $product)
            {
                array_push($products,$product);
            }
        }elseif($slug == 'discounted'){
            $all_products = Product::with('images','category','features','brand','colors','sizes','stocks','stockways','productTags','attributes','dangerousGoods')->get();
            $products = [];            
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
                    array_push($products, $product);
                } 
            }
        }elseif($slug == 'popular'){
            $top_sells = Product::with('images','category','features','brand','colors','sizes','stocks','stockways','productTags','attributes','dangerousGoods')->orderBy('total_sell','DESC')->take(2)->get();
            $products = [];
            foreach($top_sells as $product)
            {
                array_push($products,$product);
            }
        }else{
            $result = Category::descendantsAndSelf(Category::where('slug',$slug)->first());
            $products = [];
            foreach($result as $product)
            {
                foreach($product->products as $pro)
                {
                    array_push($products,$pro);
                }
            }
        }       
       
        $colors = Color::get();
        $brands = Brand::get();
        return view($this->folderName . 'category.category', compact('products','colors','brands','slug'));
    }
    public function tags_show($slug){
        $tag = Tag::where('slug',$slug)->get();
        $colors = Color::get();
        $brands = Brand::get();
        return view($this->folderName . 'tag.tag', compact('tag','colors','brands','slug'));

    }
    public function review(Request $request){
        $request->validate([
            'rating'=>'required',
            'message'=>'required'

        ]);
        Review::create($request->all());
        return redirect()->back()->with('success',"your review is added");
    }

    public function general($slug){
        switch($slug){
            case "about":
                return view('frontend.about');
                break;
            case "help":
                return view('frontend.help');
                break;
            default:
                $content = Menu::where('slug',$slug)->first();
                return view('frontend.general',compact('content'));
        }
    }

    public function billingAddress(Request $request){
        $request->validate([
            'name'=> 'required',
            'phone'=>'required|max:15|regex:/^([0-9\s\-\+\(\)]*)$/',
            'province'=>'required',
            'district'=>'required',
            'area'=>'required',
        ]);

        $data = $request->except(['province','district','area']);
        $data['province'] = Province::where('id',$request->province)->value('eng_name');
        $data['district'] = District::where('dist_id',$request->district)->value('np_name');
        $data['area'] = Local::where('id',$request->area)->value('local_name');
        $data['future_use'] = 1;
        $previous_user_in_billing = UserBillingAddress::where('user_id',auth()->guard('customer')->user()->id)->first();
            // $previous_user_in_billing->updateOrCreate($data);
        if($previous_user_in_billing != null){
            $previous_user_in_billing->update($data);
        }
        else{
            UserBillingAddress::create($data);
        }
        return redirect()->back()->with('success','Address updated successfully');

    }

    public function updateshippingaddress(Request $request){
        $request->validate([
            'name'=> 'required',
            'phone'=>'required|max:15|regex:/^([0-9\s\-\+\(\)]*)$/',
            'province'=>'required',
            'district'=>'required',
            'area'=>'required',
        ]);
        $data = $request->except(['same','payment','province','district','area']);
        $data['province'] = Province::where('id',$request->province)->value('eng_name');
        $data['district'] = District::where('dist_id',$request->district)->value('np_name');
        $data['area'] = Local::where('id',$request->area)->value('local_name');
        $previous_user_in_shipping = UserShippingAddress::where('user_id',auth()->guard('customer')->user()->id)->first();


        if($previous_user_in_shipping == null){
            $data['user_id'] = auth()->guard('customer')->user()->id;
            UserShippingAddress::create($data);
        }
        else{
            $previous_user_in_shipping = UserShippingAddress::where('user_id',auth()->guard('customer')->user()->id)->first();
            $previous_user_in_shipping->update($data);
        }
        return redirect()->back()->with('success','Address updated successfully');
    }

    public function shippingAddress(Request $request){
        $request->validate([
                    'name'=> 'required',
                    'phone'=>'required|max:15|regex:/^([0-9\s\-\+\(\)]*)$/',
                    'province'=>'required',
                    'district'=>'required',
                    'area'=>'required',
        ]);
        $data = $request->except(['same','payment','province','district','area','coupoon_code']);
        $data['province'] = Province::where('id',$request->province)->value('eng_name');
        $data['district'] = District::where('dist_id',$request->district)->value('np_name');
        $data['area'] = Local::where('id',$request->area)->value('local_name');

        // coupon related workout
        if(isset($request->coupoon_code)){
            $coupon = Coupon::where('coupon_code',$request->coupoon_code)->first();
            $data['coupon_name'] = $coupon->title;
        }


        $previous_user_in_shipping = UserShippingAddress::where('user_id',auth()->guard('customer')->user()->id)->first();
        if(!$request->future_use){
            $data['future_use'] = 0;
        }
        else{
            if($previous_user_in_shipping == null){
                $data['user_id'] = auth()->guard('customer')->user()->id;
                UserShippingAddress::create($data);
            }
            else{
                $previous_user_in_shipping = UserShippingAddress::where('user_id',auth()->guard('customer')->user()->id)->first();
                $previous_user_in_shipping->update($data);
            }

            $previous_user_in_billing = UserBillingAddress::where('user_id',auth()->guard('customer')->user()->id)->first();
            if($previous_user_in_billing != null){
                $previous_user_in_billing->update(['future_use'=>1]);
            }
        }


        if($request->same){
            $data['user_id'] = auth()->guard('customer')->user()->id;
            if($request->future_use == 1){
                $previous_user_in_billing = UserBillingAddress::where('user_id',auth()->guard('customer')->user()->id)->first();
                if($previous_user_in_billing != null){
                    $previous_user_in_billing->update($data);
                }
                else{
                    UserBillingAddress::create($data);
                }
            }
            $data = array_merge($data,['b_name'=>$request->name,'b_email'=>$request->email,'b_phone'=>$request->phone,'b_province'=>$data['province'],'b_district'=>$data['district'],'b_area'=>$data['area'],'b_zip'=>$request->zip,'b_additional_address'=>$request->additional_address]);
        }
        else{
            $previous_user_in_billing = UserBillingAddress::where('user_id',auth()->guard('customer')->user()->id)->first();
            if($previous_user_in_billing != null){
                $data['b_name'] = $previous_user_in_billing->name;
                $data['b_email'] = $previous_user_in_billing->email;
                $data['b_phone'] = $previous_user_in_billing->phone;
                $data['b_province'] = $previous_user_in_billing->province;
                $data['b_district'] = $previous_user_in_billing->district;
                $data['b_area'] = $previous_user_in_billing->area;$data['b_additional_address'] = $previous_user_in_billing->additional_address;
                $data['b_zip'] = $previous_user_in_billing->zip;
            }else{
                $user = User::where('id',auth()->guard('customer')->user()->id)->first();
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

        // payment code start from here
        if($request->payment == 'esewa'){
            $ref_id = "Glass Pipe-".Str::random(9);
            $totalAmount = $request->grandTotal;
            // $amount = Cart::sum('total_price');
            $amount = $request->grandTotal;
            $taxAmount = 0;
            $serviceCharge = 0;
            $shippingCharge = $data['shipping_charge'];
            $pid = $ref_id;
            $coupon_code = $request->coupoon_code;
            return view('payment.esewa',compact('coupon_code','totalAmount','amount','taxAmount','serviceCharge','shippingCharge','pid'));
        }
        elseif($request->payment == "COD"){
            $ref_id = "Glass Pipe-".Str::random(9);
            $cart = Cart::where('user_id',auth()->guard('customer')->user()->id)->first();
            $total_quantity = $cart->total_qty;
            $total_price = $cart->total_price;
            $total_discount = $cart->total_discount;

            // if User have Coupon
           if($request->coupoon_code){
                $discount_type = $coupon->is_percentage;
                $discount = $coupon->discount;
                if($discount_type == 'yes'){
                    $coupon_discount = (int) round(($total_price * $discount)/100);
                    $total_price = (int) round($total_price - (($total_price * $discount) / 100));
                }else{
                    $currency_id = $coupon->currency_id;
                    if($currency_id == 1){
                        $total_price = $total_price - $discount;
                        $coupon_discount = $discount;
                    }
                    // Nepali currency ko lagi matra vako xa
                }
                $total_discount = $total_discount + $coupon_discount;
           }

            // dd($total_discount);
            // create order
            $variable = ['user_id'=>auth()->guard('customer')->user()->id, 'shipping_charge'=>$data['shipping_charge'],'total_quantity'=>$total_quantity,'total_price'=>$total_price,'total_discount'=>$total_discount,'ref_id'=>$ref_id,'pending'=>1,'payment_status'=>0];
            $final_data = array_merge($data,$variable);
            unset($final_data['future_use']);
            Order::create($final_data);

            // create order assets
            $order_assets = Order::where('ref_id',$ref_id)->first();
            $order_id = $order_assets->id;
            $cart_assets = CartAssets::where('cart_id',$cart->id)->get();
            foreach($cart_assets as $cart_asset){
                $product_id = $cart_asset->product_id;
                $product_name = $cart_asset->product_name;
                $price = $cart_asset->price;
                $qty = $cart_asset->qty;
                $sub_total_price = $cart_asset->sub_total_price;
                $color = $cart_asset->color;
                $discount = $cart_asset->discount;

                // update stock
                $product_quantity = ProductStock::where(['product_id'=>$product_id])->value('quantity');
                $latest_quantity = $product_quantity - $qty;
                $product = ProductStock::where(['product_id'=>$product_id])->first();
                $product->update(['quantity'=>$latest_quantity]);

                // calculation of product total sell and update it to main product table
                $product_total_sell = Product::where('id',$product_id)->value('total_sell');
                $new_total_sell = $product_total_sell + $qty; 
                $raw_product = Product::where('id',$product_id)->first();
                $raw_product->update(['total_sell' => $new_total_sell]);

                OrderAsset::create(['order_id'=>$order_id,'product_id'=>$product_id,'product_name'=>$product_name,'price'=>$price,'qty'=>$qty,'sub_total_price'=>$sub_total_price,'color'=>$color,'discount'=>$discount]);
            }

            // create order Stock Here


            // remove cart
            $cart->delete();

            // mail to customer
            // $pdf = PDF::loadView('emails.customer.customercheckoutmailpdf', compact('orders', 'payment', 'setting'));
            $info = ['ref_id'=>$ref_id,'total_price'=>$total_price,'payment_method'=>'COD'];
            Mail::to($data['email'])->send(new OrderConfirm($data,$info));
            return redirect()->route('Corder')->with('success',"Your Order is Placed");
        }

    }

    public function oneShippingAddress(Request $request){
        $request->validate([
                    'name'=> 'required',
                    'phone'=>'required|max:15|min:9',
                    'province'=>'required',
                    'district'=>'required',
                    'area'=>'required',
        ]);
        $data = $request->except(['same','payment','province','district','area']);
        $data['province'] = Province::where('id',$request->province)->value('eng_name');
        $data['district'] = District::where('dist_id',$request->district)->value('np_name');
        $data['area'] = Local::where('id',$request->area)->value('local_name');

        if(isset($request->coupoon_code)){
            $coupon = Coupon::where('coupon_code',$request->coupoon_code)->first();
            $data['coupon_name'] = $coupon->title;
        }

        $previous_user_in_shipping = UserShippingAddress::where('user_id',auth()->guard('customer')->user()->id)->first();
        if(!$request->future_use){
            $data['future_use'] = 0;
        }
        else{
            $previous_user_in_billing = UserBillingAddress::where('user_id',auth()->guard('customer')->user()->id)->first();
            if($previous_user_in_billing != null){
                $previous_user_in_billing->update(['future_use'=>1]);
            }
        }

        if($previous_user_in_shipping == null){
            $data['user_id'] = auth()->guard('customer')->user()->id;
            UserShippingAddress::create($data);
        }
        else{
            $previous_user_in_shipping = UserShippingAddress::where('user_id',auth()->guard('customer')->user()->id)->first();
            $previous_user_in_shipping->update($data);
        }



        if($request->same){
            $data['user_id'] = auth()->guard('customer')->user()->id;
            if($request->future_use == 1){
                $previous_user_in_billing = UserBillingAddress::where('user_id',auth()->guard('customer')->user()->id)->first();
                if($previous_user_in_billing != null){
                    $previous_user_in_billing->update($data);
                }
                else{
                    UserBillingAddress::create($data);
                }
            }
            $data = array_merge($data,['b_name'=>$request->name,'b_email'=>$request->email,'b_phone'=>$request->phone,'b_province'=>$data['province'],'b_district'=>$data['district'],'b_area'=>$data['area'],'b_zip'=>$request->zip,'b_additional_address'=>$request->additional_address]);
        }
        else{
            $previous_user_in_billing = UserBillingAddress::where('user_id',auth()->guard('customer')->user()->id)->first();
            if($previous_user_in_billing != null){
                $data['b_name'] = $previous_user_in_billing->name;
                $data['b_email'] = $previous_user_in_billing->email;
                $data['b_phone'] = $previous_user_in_billing->phone;
                $data['b_province'] = $previous_user_in_billing->province;
                $data['b_district'] = $previous_user_in_billing->district;
                $data['b_area'] = $previous_user_in_billing->area;$data['b_additional_address'] = $previous_user_in_billing->additional_address;
                $data['b_zip'] = $previous_user_in_billing->zip;
            }else{
                $user = User::where('id',auth()->guard('customer')->user()->id)->first();
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
        if($request->payment == 'esewa'){
            $totalAmount = $request->grandTotal;
            $ref_id = "Glass Pipe-".Str::random(9);
            $amount = $request->grandTotal - $data['shipping_charge'];
            $taxAmount = 0;
            $serviceCharge = 0;
            $shippingCharge = $data['shipping_charge'];
            $pid = $ref_id;
            $coupon_code = $request->coupoon_code;
            return view('payment.esewa',compact('coupon_code','totalAmount','amount','taxAmount','serviceCharge','shippingCharge','pid'));
        }elseif($request->payment == "COD"){
            $ref_id = "Glass Pipe-".Str::random(9);
            $product = Product::where("id",$request->product_id)->first();
            foreach($product->stocks as $key=>$stock){
                if($key==0){
                    if($stock->special_price){
                        $price = $stock->special_price;
                        $discounts = $stock->price - $stock->special_price;
                        $color = $stock->color_id;
                    }else{
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
           if($request->coupoon_code){
                $discount_type = $coupon->is_percentage;
                $discount = $coupon->discount;
                if($discount_type == 'yes'){
                    $coupon_discount = (int) round(($total_price * $discount)/100);
                    $total_price = (int) round($total_price - (($total_price * $discount) / 100));
                }else{
                    $currency_id = $coupon->currency_id;
                    if($currency_id == 1){
                        $total_price = $total_price - $discount;
                        $coupon_discount = $discount;
                    }

                    // Nepali currency ko lagi matra vako xa
                }

                $total_discount = $total_discount + $coupon_discount;
           }

            // dd($total_discount);
            // create order
            $variable = ['user_id'=>auth()->guard('customer')->user()->id,'total_quantity'=>$total_quantity,'total_price'=>$total_price,'total_discount'=>$total_discount,'ref_id'=>$ref_id,'pending'=>1,'payment_status'=>0];
            $final_data = array_merge($data,$variable);
            unset($final_data['future_use']);
            Order::create($final_data);

            // create order assets
            $order_assets = Order::where('ref_id',$ref_id)->first();
            $order_id = $order_assets->id;


            $product_id = $request->product_id;
            $product_name = $product->name;
            $price = $total_price;
            $qty = 1;
            $sub_total_price = $total_price;
            $color = $color;
            $discount = $total_discount;

            // update stock
            $product_quantity = ProductStock::where(['product_id'=>$product_id])->value('quantity');
            $latest_quantity = $product_quantity - $qty;
            $product = ProductStock::where(['product_id'=>$product_id])->first();
            $product->update(['quantity'=>$latest_quantity]);

            // calculation of product total sell and update it to main product table
            $product_total_sell = Product::where('id',$product_id)->value('total_sell');
            $new_total_sell = $product_total_sell + $qty; 
            $raw_product = Product::where('id',$product_id)->first();
            $raw_product->update(['total_sell'=> $new_total_sell]);


            OrderAsset::create(['order_id'=>$order_id,'product_id'=>$product_id,'product_name'=>$product_name,'price'=>$price,'qty'=>$qty,'sub_total_price'=>$sub_total_price,'color'=>$color,'discount'=>$discount]);


            // create order Stock Here

            // mail to customer
            // $pdf = PDF::loadView('emails.customer.customercheckoutmailpdf', compact('orders', 'payment', 'setting'));
            $info = ['ref_id'=>$ref_id,'total_price'=>$total_price,'payment_method'=>'COD'];
            Mail::to($data['email'])->send(new OrderConfirm($data,$info));
            return redirect()->route('Corder')->with('success',"Your Order is Placed");
        }
    }

    public function getDistrict(Request $request){
        $districts = District::where('province',$request->province_id)->get();
        return response()->json([
            'districts' => $districts,
        ]);
    }

    public function getLocal(Request $request){
        $locals = Local::where('dist_id',$request->district_id)->get();
        return response()->json([
            'locals' => $locals,
        ]);
    }

    public function comment(Request $request){
        $data = $request->all();
        $data['user_id'] = auth()->guard('customer')->user()->id;
        QuestionAnswer::create($data);

        return redirect()->back()->with('success','comment added succefully');

    }

    public function traceOrder(Request $request){
        $email = $request->email;
        $user_id = New_Customer::where('email',$email)->value('id');
        if(!isset($user_id)){
            return redirect()->back()->with('error','email not found');
        }
        $refId = $request->refId;
        $order = Order::where(['user_id'=>$user_id, 'ref_id'=>$refId])->first();
        if(!isset($order)){
            return redirect()->back()->with('error','order not found');
        }
        return view('frontend.traceOrder',compact('order'));
    }




    // public function price(Request $request){
    //     $request->validate([
    //         'color'=>'required',
    //         'message'=>'required'

    //     ]);
    //     Review::create($request->all());
    //     return redirect()->back()->with('success',"your review is added");
    // }


    // public function paginate($items, $perPage = 30, $page = null, $options = [])
    // {
    //     $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
    //     $items = $items instanceof Collection ? $items : Collection::make($items);
    //     return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options = [
    //         'path' => Paginator::resolveCurrentPath()
    //     ]);
    // }

}
