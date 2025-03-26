<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductStock;
use Illuminate\Http\Request;
use App\Models\CategoryAttribute;
use App\Http\Controllers\Controller;
use App\Models\Color;
class FrontAttributeController extends Controller
{
    public function getProductAttribute(Request $request){
        $slug = $request->slug;
        $product = Product::whereSlug($slug)->firstOrFail();
        $final_data = $this->getAllAttributePrice($product);
        return response()->json([
            'data' => $final_data,
            'status' => 200,
            'message' =>"Single Product Details",
          ], 200);
    }

    private function getAllAttributePrice($product)
    {
        $stock_ways = collect(collect(collect(collect($product->stockways)->toArray())->groupBy('stock_id'))->values()->all())->toArray();
        $stock_value = [];
        $colors = [];
        $firstIteration = true;
        foreach ($stock_ways as $index => $stock_way) {
            $key_value = [];
            foreach ($stock_way as $i => $way) {
                if ($i == 0) {
                    $stock = ProductStock::where('id', $way['stock_id'])->firstOrFail();
                }
                $categoryAttribute = CategoryAttribute::findOrFail($way['key']);
                $key_value[] = [
                    'id' => $categoryAttribute->id,
                    'title' => $categoryAttribute->title,
                    'values' => [
                        $way['value']
                    ],
                    'stockId'=>$way['stock_id']
                ];
            }
           
            $selected = $firstIteration;
            $firstIteration = false;
            $stock_value[] = [
                'selected' => $selected,
                'color_id' => $stock->againColor->id,
                'color' => $stock->againColor->title,
                'price' => $stock->price ?? $product->price,
                'special_price' => $stock->special_price,
                'offer_price'=>getDetailOfferProduct($product, $stock),
                'stock_qty' => $stock->quantity,
                'attributes' => $key_value,
            ];
        }
        $first = collect($stock_value)->first();
        $attributes = collect($first['attributes'] ?? [])->toArray();

        $only_values = [];
        $only_attribute = [];
        foreach ($attributes as $i => $attribute) {
            $only_attribute[$i] = [
                'id' => $attribute['id'],
                'title' => $attribute['title'],
            ];
            $only_values[$i] = $attribute['values'];
        }

        $new_values = [];
        for ($i = 0; $i < count($only_values[0] ?? []); $i++) {
            $subArray = [];
            foreach ($only_values as $innerArray) {
                $subArray[] = $innerArray[$i];
            }
            $new_values[] = $subArray;
        }


        $colors = [];
        foreach($product->images as $img){
            if($img->color_id){
                $color = Color::findOrFail($img->color_id);
                $col = [
                    'id' => $color->id,
                    'title' => $color->title,
                    'color_code' => $color->colorCode
                ];
                $colors[] = $col;
            }
        }
        $colors = collect($colors)->unique()->values()->all();

        $st = $product->stocks()->first();
        $alternative_stock = [
            'selected' => true,
            'color_id' => $st->againColor->id ?? null,
            'color' => $st->againColor->title ?? null,
            'price' => $st->price,
            'special_price' => $st->special_price,
            'offer_price'=>getDetailOfferProduct($product, $st),
            'stock_qty' => $st->quantity,
            'attributes' => [],
        ];
       
        $data =  [
            'colors' => $colors,
            'stock' => $first ?? $alternative_stock,
            'attributes' => $only_attribute,
            'values' => $only_values,
        ];
        return $data;
    }
}
