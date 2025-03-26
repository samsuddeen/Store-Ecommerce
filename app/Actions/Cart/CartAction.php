<?php
namespace App\Actions\Cart;

use App\Models\Cart;
use App\Models\Product;
use App\Models\CartAssets;
use App\Models\Admin\VatTax;
use App\Models\ProductImage;
use App\Models\ProductStock;
use Illuminate\Http\Request;

class CartAction
{
    protected $request;
    function __construct(Request $request)
    {
        $this->request = $request;
    }
    public function addToCart($options,$varient_id)
    {
        
        $user_id = auth()->guard('customer')->user()->id;
        $product_id = $this->request->product_id;
        $title = Product::where('id', $product_id)->value('name');
        
        // $price = $this->request->price;
        $qty = $this->request->qty;
        $color_id = $this->request->color;
        $product = Product::findOrFail($product_id);
        $vatAmount=0;
        $varient_product_data=ProductStock::where('id',$varient_id)->where('product_id',$product_id)->first();

        if($varient_product_data->special_price > 0){
            // $discount=$varient_product_data->price - $varient_product_data->special_price;
            $price = $varient_product_data->special_price;
        }else{
            // $discount=0;
            $price = $varient_product_data->price;
        }
        $total_price = $price * $qty;

        $product_image=ProductImage::where('color_id',$varient_product_data->color_id)->where('product_id',$varient_product_data->product_id)->first();
        if($product_image !=null)
        {
            $selected_image=$product_image->image;
        }
        else
        {
            $selected_image=null;
        }
        $discount=$varient_product_data->price-$price;
       
        foreach($product->stocks as $key=>$stock){
            if($key==0){
                $availableStock = $stock->quantity;
            }else{
                break;
            }
        }
        $data = ['user_id'=>$user_id,'product_id'=>$product_id,'title'=>$title,'price'=>$price,'qty'=>$qty,'color_id'=>$color_id,'total_price'=>$total_price];
        $data_exist = Cart::with('cartAssets')->where('user_id',$user_id)->first();
        if(!empty($data_exist)){
            $previous_total_qty = Cart::where('user_id',$user_id)->value('total_qty');
            $previous_total_price = Cart::where('user_id',$user_id)->value('total_price');
            $previous_total_discount = Cart::where('user_id',$user_id)->value('total_discount');
            
            if($product->vat_percent==0)
                {
                    $vatTax=VatTax::first();
                    $vatPercent=(int)$vatTax->vat_percent;
                    $vatAmount=($total_price*$vatPercent)/100;
                    $total_price=$total_price+round($vatAmount);
                }
                else
                {
                    $total_price=$total_price;
                    $vatAmount=0;
                }
            $new_total_price = $previous_total_price + $total_price;
            $new_total_qty = $previous_total_qty + $qty;
            $new_total_discount = $previous_total_discount + $discount;
            $product_in_assets = $data_exist->cartAssets->where('product_id', $product_id)->where('color', $color_id)->where('cart_id', $data_exist->id)->first();
            if(!empty($product_in_assets)){
                $total_qty_on_cart = $product_in_assets->qty + $qty;
                if($availableStock < $total_qty_on_cart){
                    return response()->json([
                        'error' => 'Error',
                    ]);
                }
            }
            elseif($availableStock < $qty){
                return response()->json([
                    'error' => 'Error',
                ]);
            }
            Cart::where('user_id', $user_id)->update(['total_qty'=>$new_total_qty,'total_price'=>$new_total_price,'total_discount'=>$new_total_discount]);
            $cart = Cart::where('user_id', $user_id)->first();
            $cart_assets = $cart->cartAssets->where('product_id', $product_id)->where('color', $color_id)->where('cart_id', $cart->id)->where('varient_id',$varient_id)->first();
            // dd('sumit',$cart_assets);
            if(!empty($cart_assets)){
                $new_sub_total_price = $cart_assets->sub_total_price + $total_price;
                $new_qty = $cart_assets->qty + $qty;
                $new_discount = $cart_assets->discount + $discount;
                if($product->vat_percent==0)
                {
                    $vatTax1=VatTax::first();
                    $vatPercent=(int)$vatTax1->vat_percent;
                    // dd($cart_assets->price*$new_qty);
                    $vatAmount1=(($cart_assets->price*$new_qty)*$vatPercent)/100;
                }
                else
                {
                    $vatAmount1=0;
                }
                // dd($vatAmount);
                $cart_assets->update([
                    'qty'=>$new_qty,
                    'sub_total_price'=>$new_sub_total_price,
                    'discount'=>$new_discount,
                    'options'=>json_encode($options),
                    'vatamountfield'=>round($vatAmount1) ?? 0
                ]);
            }else{
                // dd('2');
                // if($product->vat_percent==0)
                //     {
                //         $vatTax=VatTax::first();
                //         $vatPercent=(int)$vatTax->vat_percent;
                //         $vatAmount=($total_price*$vatPercent)/100;
                //         $total_price=$total_price+round($vatAmount);
                //     }
                //     else
                //     {
                //         $total_price=$total_price;
                //         $vatAmount=0;
                //     }
                $cart_assets=CartAssets::create([
                    'cart_id'=>$cart->id,
                    'product_id'=>$product_id,
                    'product_name'=>$title,
                    'price'=>$price,
                    'varient_id'=>$varient_id,
                    'qty'=>$qty,
                    'sub_total_price'=>$total_price,
                    'color'=>$color_id,
                    'image'=>$selected_image,
                    'discount'=>$discount,
                    'options'=>json_encode($options),
                    'vatamountfield'=>round($vatAmount) ?? 0
                ]);
            }
        }
        else{
            $vatAmount=0;
            // if($product->vat_percent==0)
            // {
            //     $vatTax=VatTax::first();
            //     $vatPercent=(int)$vatTax->vat_percent;
            //     $vatAmount=($total_price*$vatPercent)/100;
            //     $total_price=$total_price+round($vatAmount);
            // }
            // else
            // {
            //     $total_price=$total_price;
            //     $vatAmount=0;
            // }
            if($availableStock < $qty){
                return response()->json([
                    'error' => 'Error',
                ]);
            }
          $check =   Cart::create([
                'user_id'=>$user_id,
                'total_price'=>$total_price,
                'total_qty'=>$qty,
                'total_discount'=>$discount*$qty,
            ]);
            // dd($check);
            $cart_id = Cart::where('user_id', $user_id)->value('id');
            $cart_assets=CartAssets::create([
                'cart_id'=>$cart_id,
                'product_id'=>$product_id,
                'product_name'=>$title,
                'price'=>$price,
                'varient_id'=>$varient_id,
                'qty'=>$qty,
                'sub_total_price'=>$total_price,
                'color'=>$color_id,
                'image'=>$selected_image,
                'discount'=>$discount,
                'options'=>json_encode($options),
                'vatamountfield'=>round($vatAmount) ?? 0
            ]);
        }
        $cart = Cart::with('cartAssets')->where('user_id', $user_id)->first();
        $data=[
            'cart'=>$cart,
            'cart_asset'=>$cart_assets,
        ];
        return $data;   
    }
}