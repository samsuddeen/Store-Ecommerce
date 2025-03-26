<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\{
    CartAssets,
    User,
    Product,
    Wishlist as WishList,
    Cart,
    Order
};
use Illuminate\Support\Facades\DB;
class WishListController extends Controller
{
    protected $user = null;
    protected $product = null;
    protected $wishlist = null;
    protected $cart = null;
    protected $cart_asset = null;

    public function __construct(User $user, Product $product, WishList $wishlist, Cart $cart, CartAssets $cart_asset)
    {
        $this->user = $user;
        $this->product = $product;
        $this->wishlist = $wishlist;
        $this->cart = $cart;
        $this->cart_asset = $cart_asset;
    }

    public function addToWishList(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required',
        ]);

        if ($validator->fails()) {
            $response = [
                'error' => true,
                'data' => null,
                'message' => $validator->errors()
            ];
            return response()->json($response, 500);
        }

        $user = \Auth::user();
        $product = $this->product->find($request->product_id);

        if (!$user) {
            $response = [
                'error' => true,
                'data' => null,
                'msg' => 'Sorry ! User Not Found'
            ];

            return response()->json($response, 500);
        }

        if (!$product) {
            $response = [
                'error' => true,
                'data' => null,
                'msg' => 'Sorry ! Product Not Found'
            ];

            return response()->json($response, 500);
        }
        DB::beginTransaction();
       try{
            $this->wishlist->updateOrCreate(
                [
                    'user_id' => $user->id,
                    'product_id' => $product->id
                ],
                [
                    'user_id' => $user->id,
                    'product_id' => $product->id
                ]
            );

            $response = [
                'error' => false,
                'msg' => 'Items Successfully Added In The Wish List !!'
            ];
            DB::commit();
            return response()->json($response, 200);
       }catch(\Throwable $th){
           DB::rollBack();
            $response = [
                'error' => true,
                'msg' => $th->getMessage()
            ];

            return response()->json($response, 200);
       }
    }

    public function wishlist(Request $request)
    {
        $this->user = \Auth::user();
        if (!$this->user) {
            $response = [
                'error' => true,
                'data' => null,
                'msg' => 'Sorry !! Unauthorized User !'
            ];
            return response()->json($response, 500);
        }

        $wishlist = collect($this->wishlist->where('user_id', $this->user->id)->get())->groupBy('product_id');
        if ($wishlist->count() <= 0) {
            $response = [
                'error' => true,
                'data' => null,
                'msg' => 'No Item In The Wish List !'
            ];
            return response()->json($response, 500);
        }
        $final_data = [];
        foreach ($wishlist as $key => $data) {
            $product = Product::where('id', $key)->with('images', 'getPrice')->first();
            if(getOfferProduct($product,$product->stocks[0]) !=null)
            {
                $product['getPrice']['special_price']=getOfferProduct($product,$product->stocks[0]);
            }
            $product->makeHidden([
                'total_sell',
                'short_description',
                'long_description',
                'min_order',
                'returnable_time',
                'delivery_time',
                'keyword',
                'package_weight',
                'dimension_length',
                'dimension_width',
                'dimension_height',
                'warranty_type',
                'warranty_period',
                'warranty_policy',
                'brand_id',
                'country_id',
                'category_id',
                'user_id',
                'url',
                'publishStatus',
                'created_at',
                'updated_at',
                'stocks'
            ]);

            foreach ($product->images as $image) {
                $image->makeHidden([
                    "color_id",
                    "product_id",
                    "is_featured",
                    "created_at",
                    "updated_at"
                ]);
            }
            $product->setAttribute('varient_id', $product->stocks[0]->id);
            $final_data['get_product'][] = $product;
        }
        $response = [
            'error' => false,
            'data' => $final_data,
            'msg' => 'Wish List Items Details'
        ];
        return response()->json($response, 200);

    }

    public function removeFromWishList(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required',
        ]);
        if ($validator->fails()) {
            $response = [
                'error' => true,
                'data' => null,
                'message' => $validator->errors()
            ];
            return response()->json($response, 500);
        }

        $user = \Auth::user();

        $wishlist = $this->wishlist->where('product_id', $request->product_id)->where('user_id',$user->id)->first();
        if (!$user) {
            $response = [
                'error' => true,
                'data' => null,
                'msg' => 'Sorry !! User Not Found'
            ];
            return response()->json($response, 500);
        }

        if (!$wishlist) {
            $response = [
                'error' => true,
                'data' => null,
                'msg' => 'Sorry !! Product Not Found To Remove From Wish List'
            ];
            return response()->json($response, 500);
        }

        $del = $this->wishlist->where('product_id', $wishlist->product_id)->where('user_id', $user->id)->delete();

        if ($del) {
            $response = [
                'error' => false,
                'msg' => 'Wish List Updated Successfully !!'
            ];

            return response()->json($response, 200);
        } else {
            $response = [
                'error' => true,
                'data' => null,
                'msg' => 'Sorry ! There Was A Problem While Deleting From Product From List !!'
            ];

            return response()->json($response, 500);
        }
    }

    public function clearWishlist(Request $request)
    {
        $user = \Auth::user();
        if (!$user) {
            $response = [
                'error' => true,
                'data' => null,
                'msg' => 'Unauthorized User  !!'
            ];

            return response()->json($response);
        }

        $wish_list_data = WishList::where('user_id', $user->id)->get();

        if (count($wish_list_data) > 0) {
            WishList::where('user_id', $user->id)->delete();

            $response = [
                'error' => true,
                'data' => null,
                'msg' => 'WishList Cleared Successfully !!'
            ];

            return response()->json($response, 200);
        } else {
            $response = [
                'error' => true,
                'data' => null,
                'msg' => 'Sorry !! No Item In The WishList'
            ];

            return response()->json($response, 500);
        }
    }

    public function downloadMobilePdfFile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required',
        ]);
        if ($validator->fails()) {
            $response = [
                'error' => true,
                'data' => null,
                'message' => $validator->errors()
            ];
            return response()->json($response, 200);
        }
        $order=Order::where('id',$request->order_id)->first();
        if(!$order)
        {
            $response = [
                'error' => true,
                'data' => null,
                'message' => 'Invalid Order Id !!'
            ];
            return response()->json($response, 200);
        }
        

        
        $response = [
            'error' => false,
            'data' => asset('mobilepdf/'.$order->id.'.pdf'),
            'message' => 'Pdf File Path !!'
        ];
        return response()->json($response, 200);
    }
}
