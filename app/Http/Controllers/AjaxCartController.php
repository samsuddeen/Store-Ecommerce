<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use App\Rules\CheckStock;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class AjaxCartController extends Controller
{
    public function getAddToCart(Request $request)
    {

        $id = $request->productId;
        $product = Product::findOrFail($id);
        $validation =    Validator::make($request->all(), [
            'qty' => [new CheckStock($id)]
        ]);

        if ($validation->fails()) {
            return response()->json([
                'error' => $validation->getMessageBag(),
                'status' => 422,
            ], 422);
        }
        $product->setAttribute('quantity', $request->qty);
        $product->setAttribute('aff_id', $request->aff_id ?? '0');
        // dd($request->aff_id);

        $oldCart = Session::has('cart') ? Session::get('cart') : null;

        $cart = new Cart($oldCart);
        $cart->add($product, $product->id);
        $request->session()->put('cart', $cart);

        $cartProducts = $cart->items;
        return view('frontend.cart.cart', compact('cartProducts'))->with('status', 'success');
    }

    public function getRemoveFromCart(Request $request)
    {
        $oldCart = Session::has('cart') ? Session::get('cart') : null;
        $cart = new Cart($oldCart);
        $cart->removeItem($request->productId);

        if (count($cart->items) > 0) {
            Session::put('cart', $cart);
        } else {
            Session::forget('cart');
        }

        $cartProducts = $cart->items;

        return view('frontend.cart.cart', compact('cartProducts'));
    }

    public function getUpdateCart(Request $request)
    {
        Session::forget('cart');

        foreach ($request->id as $key => $id) {

            $product = Product::findOrFail($id);
            $product->setAttribute('quantity', $request->qty[$key]);
            $product->setAttribute('aff_id', $request->aff_id[$key]);

            $oldCart = Session::has('cart') ? Session::get('cart') : null;
            $cart = new Cart($oldCart);
            $cart->add($product, $product->id);

            $request->session()->put('cart', $cart);
        }

        $oldCart = Session::has('cart') ? Session::get('cart') : null;
        $cart = new Cart($oldCart);
        $cartProducts = $cart->items;

        return view('front.cart.cart', compact('cartProducts'));
    }
}
