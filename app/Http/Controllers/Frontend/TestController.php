<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Product;
use App\Events\LogEvent;
use App\Models\Category;
use Illuminate\Support\Str;
use App\Models\ProductStock;
use Illuminate\Http\Request;
use App\Actions\Cart\CartAction;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class TestController extends Controller
{
    public function addToCart(Request $request)
    {
        if (!auth()->guard('customer')->user()) {
            $url = '/customer/login';
            return redirect($url);
        }

        $options  = [];
        if ($request->has('key')) {
            if (count($request->key) > 0) {
                foreach ($request->key as $index => $k) {
                    $options[] = [
                        'id' => $k,
                        'title' => $request->key_title[$index],
                        'value' => $request->value[$index],
                    ];
                }
            }
        }

        // $varient_id = ProductStock::where('product_id', $request->product_id)->where('color_id', $request->color)->first();
        $varient_id = $request->varient_id;
        $stock_id = ProductStock::where('id', $varient_id)->where('product_id', $request->product_id)->where('color_id', $request->color)->first();
        if ((int)$stock_id->quantity <= 0 || $request->qty > (int)$stock_id->quantity) {
            $response=[
                'error'=>true,
                'data'=>null,
                'msg'=>'Sorry !! Product Is Outof Stocks'
            ];
            return response()->json($response,200);
            // $request->session()->flash('error', 'Sorry !! Product Is Outof Stocks');
            // return redirect()->back();
        }

        DB::beginTransaction();
        try {
           
        $data=(new CartAction($request))->addToCart($options, $varient_id);
        // dd($data);
        
        LogEvent::dispatch('Add to Cart', 'Add to Cart', route('cart.add-to-cart'));    
       
        DB::commit();
        // dd($cart);
        $response=[
            'error'=>false,
            'data'=>$this->getModalTest($data),
            'msg'=>'Added To The Cart Successfully !!'
        ];
        return response()->json($response,200);
       
        } catch (\Throwable $th) {
            DB::rollBack();
            $request->session()->flash('error','Something Went Wrong!!');
            return back();
        }
    }

    public function getModalTest($data=null)
    {
        $product_id=$data['cart_asset']->product_id;
        $product=Product::find($product_id);
        $related_products = Product::where('category_id',$product->category_id)->inRandomOrder()->take(10)->get(); 

        $html='';
        $html.='<div class="selfcart-wrap" id="modal_detail">';
        $html.='<div class="selfcart-head">';
        $html.='<div class="selfcart-head-left">';
        $html.='<p><i class="las la-check-circle"></i> 1 new item(s) have been added to your cart</p>';
        $html.='<div class="selfcart-product">';
        $html.='<div class="selfcart-product-media">';
        $html.='<a href="#" title="Brand Quality Earphone With Mic Hi Tech E535 3.5mm">';
        $html.='<img src="'.$data['cart_asset']->image.'" alt="images">';
        $html.='</a>';
        $html.='</div>';
        $html.='<div class="selfcart-product-info">';
        $html.='<h3><a href="#">'.$data['cart_asset']->name.'</a></h3>';
        $html.='<span>Brand: Acer, Color: '.$data['cart_asset']->color.'</span>';
        $html.='<b>$. '.$data['cart_asset']->price.'</b>';
        $html.='<div class="selfcart-price">';
        $html.='<div class="selfcart-price-left">';
        $html.='<del>$. '.$data['cart_asset']->price.'</del>';
        $html.='<span>-50%</span>';
        $html.='</div>';
        $html.='<em>Qty: <b>'.$data['cart_asset']->qty.'</b></em>';
        $html.='</div>';
        $html.='</div>';
        $html.='</div>';
        $html.='</div>';
        $html.='<div class="selfcart-head-right">';
        $html.='<div class="selfcart-right-head">';
        $html.='<h3>My Shopping Cart</h3>';
        $html.='<span>('.$data['cart']->total_qty.' items)</span>';
        $html.='</div>';
        $html.='<ul>';
        $html.='<li>';
        $html.='<span>Subtotal</span>';
        $html.='<b>$. '.$data['cart']->total_price.'</b>';
        $html.='</li>';
        $html.='<li>';
        $html.='<span>Total</span>';
        $html.='<b>$.'.$data['cart']->total_price.'</b>';
        $html.='</li>';
        $html.='</ul>';
        $html.='<div class="btn-groups">';
        $html.='<a href="'.route('cart.index').'" class="btns">Go To Cart</a>';
        $html.='<button type="submit" class="btns">Checkout</button>';
        $html.='</div>';
        $html.='</div>';
        $html.='</div>';
        $html.='<div class="selfcart-body mt">';
        $html.='<div class="overall-product">';
        $html.='<div class="main-title">';
        $html.='<h3>Jus For You</h3>';
        $html.='</div>';
        $html.='<ul>';
        foreach($related_products as $product)
        {
            $html.='<li>';
            $html.='<div class="product-col">';
            $html.='<div class="product-media">';
            $html.='<a href="'.route('product.details', $product->slug).'" title="'.$product->name.'">';
            $html.='<img src="'.$product->images->where('is_featured', true)->first()->image ?? null.'" alt="images" >';
            $html.='</a>';
            $html.= getDiscountValue($product);
            $html.='</div>';
            $html.='<div class="product-content">';
            $html.='<h3>';
            $html.='<a href="#">';
            $html.='<a href="'.route('product.details', $product->slug).'">'.$product->slug.Str::limit($product->name, 40, $end = '...').'</a>';
            $html.='</h3>';
            $html.='<div class="price-group">';
            $html.='<div class="old-price-list">';
            foreach ($product->stocks as $key => $stock)
                {
                    if ($key == 0)
                    {
                        $offer = getOfferProduct($product, $stock);
                        if ($offer != null)
                           {
                            $html.='<del>$.'.number_format($stock->price).'</del>';
                            
                            if ($stock->special_price != null)
                               {
                                $html.='<del>$. '.number_format($stock->special_price).'</del>';
                               }
                               $html.='<p class="price_list">$. '.number_format($offer).'</p>';
                           }
                        elseif($stock->special_price)
                            {
                                $html.='<del>$. '.number_format($stock->price).'</del>';
                                $html.='<p class="price_list">$. '.number_format($stock->special_price).'</p>';  
                            }
                        else
                           {
                            $html.='<p class="price_list">$. '.number_format($stock->price).'</p>';
                           }
                    }
                }
            $html.='</div>';
            $html.='</div>';
            $html.='</div>';
            $html.='</div>';
            $html.='</li>';
        }
        $html.='</ul>';
        $html.='</div>';
        $html.='</div>';
        $html.='</div>';

        return $html;
    }
    //new-to-cart
}
