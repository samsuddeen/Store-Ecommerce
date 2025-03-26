<?php

namespace App\Http\Controllers\Api\Offer;

use App\Data\Color\ColorData;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Admin\Offer\TopOffer;
use App\Models\Admin\Product\Featured\FeaturedSection;
use Carbon\Carbon;

class TopOfferController extends Controller
{
    //
    function index(Request $request)
    {
        $now = Carbon::now()->toDateString();
        $top_offer = TopOffer::with(['offerProducts'])->orderBy('created_at')->whereDate('from', '<=', $now)->whereDate('to', '>=', $now)->where('status', 1)->get();
        $top_offers = collect($top_offer)->map(function ($row1, $index1) {
            return [
                'title' => $row1->title,
                'slug' => $row1->slug,
                'image' => $row1->image,
                'from' => $row1->from,
                'to' => $row1->to,
                'offer' => $row1->offer,
                'is_fixed' => $row1->is_fixed,
                'products'=>collect($row1->offerProducts)->map(function($row2, $index2) use($row1){
                   return[
                        "id" => $row2->id,
                        "name" => $row2->name ,
                        "slug" => $row2->slug ,
                        "total_sell" => $row2->total_sell ,
                        "short_description" => $row2->short_description ,
                        "long_description" => $row2->long_description ,
                        "min_order" => $row2->min_order ,
                        "returnable_time" =>$row2->returnable_time ,
                        "delivery_time" => $row2->delivery_time ,
                        "keyword" => $row2->keyword ,
                        "package_weight" =>$row2->package_weight ,
                        "dimension_length" => $row2->dimension_length ,
                        "dimension_width" => $row2->dimension_width ,
                        "dimension_height" => $row2->dimension_height ,
                        "warranty_type" =>$row2->warranty_type ,
                        "warranty_period" => $row2->warranty_period ,
                        "warranty_policy" => $row2->warranty_policy ,
                        "brand_id" => $row2->brand_id ,
                        "country_id" => $row2->country_id ,
                        "category_id" => $row2->category_id ,
                        "rating" => $row2->rating ,
                        "publishStatus" =>$row2->publishStatus ,
                        "url" => $row2->url ,
                        // for the top offer 
                        "color_id" => (new ColorData())->getColorTitle($row2->stocks[0]->color_id),
                        'price'=>$row2->stocks[0]->price,
                        "orginal_price" => $row2->stocks[0]->price,
                        'offer_price'=>$this->getPrice($row1->offer, $row1->is_fixed, $row2->stocks[0]->price),
                        'image'=>$row2->productImages[0]->image
                   ];
                })
            ];
        });
        $top_offers = collect($top_offers)->toArray();
        $response = [
            'status' => 200,
            'msg' => 'Top Offer List',
            'data' =>  $top_offers,
        ];
        return response()->json($response, 200);
    }


    public function products($slug)
    {
        $now = Carbon::now()->toDateString();


        $offer = TopOffer::orderBy('created_at')->where('slug', $slug)->whereDate('from', '<=', $now)->whereDate('to', '>=', $now)->where('status', 1)->first();

        $product = $offer->offerProducts;
        $data = [];
        $data['id'] = $offer->id;
        $data['title'] = $offer->title;
        $data['slug'] = $offer->slug;
        $data['from'] = $offer->from;
        $data['to'] = $offer->to;
        $data['is_fixed'] = $offer->is_fixed;
        $data['offer'] = $offer->offer;
        $data['image'] = $offer->image;
        $data['status'] = $offer->status;
        $data['created_at'] = $offer->created_at;
        $data['updated_at'] = $offer->updated_at;

        foreach ($product as $key => $p) {
            $data['offer_products'][$key] = $p;
            $data['offer_products'][$key]['varient_id'] = $p->stocks[0]->id;
            $data['offer_products'][$key]['product_images'] = $p->images;
            $data['offer_products'][$key]->makeHidden(['images']);
            if (getOfferProduct($p, $p->stocks[0]) != null) {
                $data['offer_products'][$key]['stocks'][0]->special_price = getOfferProduct($p, $p->stocks[0]);
            }
        }
        $response = [
            'status' => 200,
            'msg' => 'Top Offer List',
            'data' =>  $data,
        ];
        return response()->json($response, 200);
    }

    public function getPrice($offer, $fixed, $price)
    {
        if ($fixed == 1) {
            return $price - $offer;
        } else {
            return $price - (($price * $offer) / 100);
        }
    }
}
