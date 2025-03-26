<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Admin\Offer\Product\TopOfferProduct;
use App\Models\Admin\Offer\TopOffer;
use App\Models\New_Customer;

class RecommendationProductController extends Controller
{
    public function recommendation(Request $request)
    {
        
        $userType=$request->user_type ?? null;

        $product = Product::with('images', 'stocks')
            ->select('id', 'name', 'slug', 'rating','product_for')

            ->where('status', 1)
            ->where('publishStatus', '1')

            ->inRandomOrder()
            ->orderBy('created_at', 'DESC')
            ->limit(10)
            ->get();
        if($userType && $userType !=null){
            if($userType=='2'){
                $product=$product->where('product_for','!=','2')->values();
            }else{
                $product = $product->where('product_for', '!=', '1')->map(function($item) {
                    $item->stocks[0]->price = $item->stocks[0]->wholesaleprice;
                    return $item; 
                })->values();
            }
        }


        try {
            foreach ($product as $data) {
                $offer = getOfferProduct($data, $data->stocks[0]);
                if ($offer != null) {
                    $data['getPrice']->special_price = $offer;
                }
                $data->setAttribute('varient_id', $data->stocks[0]->id);
                $data->setAttribute('percent',  apigetDiscountPercnet($data->id) ?? null);
            }
            $response = [
                'error' => false,
                'data' => $product,
                'msg' => 'Data Successfully fetched.'
            ];

            return  response()->json($response);
        } catch (\Exception $ex) {
            $response = [
                'error' => true,
                'data' => null,
                'msg' => $ex->getMessage()
            ];
            return response()->json($response, 200);
        }
    }


    public function specialOfferProduct(Request $request)
    {


        $date = date('Y-m-d');
        $top_offer = TopOffer::where('to', '>=', $date)->first();
        $userType=$request->user_type ?? null;
        if ($top_offer) {
            $offer_productIds = TopOfferProduct::where('top_offer_id', $top_offer->id)
                ->pluck('product_id')
                ->toArray();

            $products = Product::with('images', 'stocks')
                ->select('id', 'name', 'slug', 'rating','product_for')
                ->whereIn('id', $offer_productIds)
                ->where('status', 1)
                ->where('publishStatus', '1')
                ->orderBy('created_at', 'DESC')
                ->get();
                if($userType && $userType !=null){
                    if($userType=='2'){
                        $products=$products->where('product_for','!=','2')->values();
                    }else{
                        $products = $products->where('product_for', '!=', '1')->map(function($item) {
                            $item->stocks[0]->price = $item->stocks[0]->wholesaleprice;
                            return $item; 
                        })->values();
                    }
                }
            foreach ($products as $product) {
                $stocks = $product->stocks;

                foreach ($stocks as $stock) {
                    if ($top_offer->is_fixed == 1) {
                        $discountedPrice = $stock->price - $top_offer->offer;
                    } elseif ($top_offer->is_fixed == 0) {
                        $discountedPrice = round(($top_offer->offer * $stock->price) / 100, 0);
                    }

                    // Ensure discounted price is not negative
                    $discountedPrice = max(0, $discountedPrice);

                    // Add discounted price to the stock object
                    $stock->special_price = $discountedPrice;
                }

                // Replace the stocks in the product with the modified stocks
                $product->stocks = $stocks;
            }

            return   $response = [
                'error' => false,
                'data' => $products,
                'msg' => 'Data Successfully fetched.'
            ];
    
        } else {
            // Handle case when no top offer is available
            return   $response = [
                'error' => false,
                'data' => 'No data found',
                'msg' => 'Data Successfully fetched.'
            ];
        }



    }

    public function latestProducts(Request $request)
    {
        $userType=$request->user_type ?? null;
        $product = Product::with('images', 'stocks')
        ->select('id', 'name', 'slug', 'rating','product_for')
        ->orderBy('created_at','desc')
        ->where('status', 1)
        ->where('publishStatus', '1')

        ->inRandomOrder()
        ->orderBy('created_at', 'DESC')
        ->limit(10)
        ->get();
        if($userType && $userType !=null){
            if($userType=='2'){
                $product=$product->where('product_for','!=','2')->values();
            }else{
                $product = $product->where('product_for', '!=', '1')->map(function($item) {
                    $item->stocks[0]->price = $item->stocks[0]->wholesaleprice;
                    return $item; 
                })->values();
            }
        }

    try {
        foreach ($product as $data) {
            $offer = getOfferProduct($data, $data->stocks[0]);
            if ($offer != null) {
                $data['getPrice']->special_price = $offer;
            }
            $data->setAttribute('varient_id', $data->stocks[0]->id);
            $data->setAttribute('percent',  apigetDiscountPercnet($data->id) ?? null);
        }
        $response = [
            'error' => false,
            'data' => $product,
            'msg' => 'Data Successfully fetched.'
        ];

        return  response()->json($response);
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