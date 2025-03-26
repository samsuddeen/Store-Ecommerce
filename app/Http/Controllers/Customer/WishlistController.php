<?php

namespace App\Http\Controllers\Customer;

use App\Models\Order;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use App\Enum\Order\OrderStatusEnum;
use App\Http\Controllers\Controller;

class WishlistController extends Controller
{
    public function wishlist(){
        $wishlists = Wishlist::where('user_id',auth()->guard('customer')->user()->id)->get();        
        $past_purchases = Order::where(['user_id'=>auth()->guard('customer')->user()->id])->where('status', OrderStatusEnum::DELIVERED)->get();

        $allProducts = [];
        foreach($past_purchases as $order){
            foreach($order->orderAssets as $data){
                $product = $data->product;
                array_push($allProducts,$product);
            }
        }
        return view('frontend.customer.wishlist',compact('wishlists','allProducts'));
    }
    
    public function removeWishlistProduct($id){
        $wishlist = Wishlist::where('product_id',$id)->first();
        $wishlist->delete();
        return redirect()->back()->with('success','Product Remove Successfully');
    }
}
