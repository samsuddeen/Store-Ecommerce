<?php
namespace App\Http\Controllers;
use App\Models\Color;
use App\Models\Product;
use App\Models\ProductStock;
use Illuminate\Http\Request;
use App\Models\CategoryAttribute;
use App\Http\Controllers\Controller;
use App\Models\StockWays;
use App\Models\ProductImage;
class ProductAttributeDetailController extends Controller
{    
    public function index(Request $request)
    {

        if($request->type == 'change_color'){
            if($request->color){
                $color = Color::find($request->color)->first();
            }
            $product = Product::find($request->product_id);
            $productStock = ProductStock::where('product_id', $request->product_id)->get();
            $stocks = $productStock;
            $data = $this->getFinalStocks($stocks, $product);
            if($request->ajax()){
                return response()->json($data, 200);
            }else{
                return $data;
            }
        }
    }
    public function getFinalStocks($stocks, $product)
    {   
        $price = 0;
        $html_price = [];
        $stock_ways = [];
        $paired_values = [];
        $keys = [];
        $new_price = [];
        $final_data = $this->getData($product);
        foreach($stocks as $index=>$stock){
            if((collect($stocks)->toArray()) == $index){
                if($stock['special_price'] !=null)
                {
                    $price = $stock['special_price'];
                }
                else
                {
                    $price = $stock['price'];
                }
                $original_price=$stock['price'];
                $specail_price=$stock['special_price'];
            }
            $stock_ways[] = $stock->stockWays;
        }
        $stock_ways  = collect($stock_ways)->toArray();
        // dd($final_data);
        foreach($stock_ways as $index=>$stock_way){
            foreach($stock_way as $way){
                $categoryAttribute = CategoryAttribute::find($way['key']);
                $paired_values[$way['key']][] = $way['value'];
                $stock_w = StockWays::where('stock_id', $way['stock_id'])->first();
                $html_price[$way['key']][$way['value']] = $stock_w->stock->price;
                $html_price[$way['key']]['price'][$way['value']] =( getDetailOfferProduct($product,$stock_w->stock)) ? getDetailOfferProduct($product,$stock_w->stock) :(($stock_w->stock->special_price) ? $stock_w->stock->special_price :'');
                $html_price[$way['key']]['stock_quantity'][$way['value']] =$stock_w->stock->quantity ?? 0;
                $html_price[$way['key']]['varient_id'][$way['value']] =$stock_w->stock->id ?? 0;
                $html_price[$way['key']]['mimquantity'][$way['value']] =$stock_w->stock->mimquantity ?? 0;
                $html_price[$way['key']]['colorCode'][$way['value']] =$stock_w->stock->getColor->colorCode?? 0;
                $p=[
                    'value'=>$way['value'],
                    'price'=>$stock_w->stock->price
                ];
                $new_price[$way['key']][] =$p;
            }
        }
        $html_string = "";
        foreach($final_data['keys'] as $indexData=>$key){
            $html_string .= '<div class="col-md-4 col-sm-6"><div class="form-group">';
            $html_string .='<label>'.$key['title'].'</label>';
            $html_string .= '<input type="hidden" name="key_title[]" value="'.$key['title'].'">';
            $html_string .='<input type="hidden" name="key[]" value="'.$key['id'].'">';
            $html_string .='<select id="key-id-'.$key['id'].'" name="value[]" class="form-control product-attribute attribute" data-key_id="'.$key['id'].'" data-key_title="'.$key['title'].'">';
                    foreach ($final_data['values'][$key['id']] as $value){
                        $html_string .='<option value="'.$value.'" data-value="'.$value.'"';
                        $html_string .= ' data-price="';
                        $html_string .= (in_array($value, $paired_values[$key['id']])) ? $html_price[$key['id']][$value] : '';
                        $html_string .='"';

                        $html_string .= ' data-colorcode="';
                        $html_string .= $html_price[$key['id']]['colorCode'][$value];
                        $html_string .='"';

                        $html_string .= ' data-sprice="';
                        $html_string .= $html_price[$key['id']]['price'][$value] ?? '';
                        $html_string .='"';
                        $html_string .= ' data-varientId="';
                        $html_string .= $html_price[$key['id']]['varient_id'][$value] ?? '';
                        $html_string .='"';
                        $html_string .= ' data-stockquantity="';
                        $html_string .= $html_price[$key['id']]['stock_quantity'][$value] ?? '';
                        $html_string .='"';
                        $html_string .= " data-availability=' ";
                        $html_string .= (in_array($value, $paired_values[$key['id']])) ? json_encode($new_price) : '';
                        $html_string .="'";
                        $html_string .= ' data-mimquantity="';
                        $html_string .= $html_price[$key['id']]['mimquantity'][$value] ?? 1;
                        $html_string .='"';
                        $html_string .= ($paired_values[$key['id']][0] == $value) ? ' selected' : '';
                        $html_string .= (!in_array($value, $paired_values[$key['id']]));
                        $html_string .='>';
                        $html_string .= $value;
                        $html_string .= '</option>';
                    }
            $html_string .='</select>';
            $html_string .= '</div></div>';
        }
        $offer_price = [];
        $original_price = [];
        $specail_price = [];
        foreach ($stocks as $stock) {
            $offer_prices[] = getDetailOfferProduct($product, $stock);
            $original_price[] = $stock['price'];
            $specail_price[] = $stock['special_price'] ?? null;
        }
        
        // $offer_price=getDetailOfferProduct($product,$stocks[0]);
        // $original_price=$original_price;
        // $specail_price=$specail_price;
        if($offer_price !=null)
        {   
            $stock_wise_price="<div class='detail_discount'><del class='original_price'></del>".getDetailDiscount($offer_price[0],$original_price[0])."</div><span class='offer_price'>Rs.".number_format($offer_price[0])."</span>";
        }
        elseif($offer_price == null && $specail_price !=null)
        {   
            $stock_wise_price="<div class='detail_discount'><del class='original_price'></del>".getDetailDiscount($specail_price[0],$original_price[0])."</div><span class='special_price'>Rs.".number_format($specail_price[0])."</span>";
        }
            
        else {
            $stock_wise_price="<span class='original_price'>Rs.". number_format($original_price[0])."</span>";
        }
        
        $product_color=ProductImage::where('color_id', $stocks[0]->color_id ?? null)->where('product_id', $stocks[0]->product_id ?? 0)->first();
        $color_image= collect(ProductImage::where('color_id', $stocks[0]->color_id ?? null)->where('product_id', $stocks[0]->product_id ?? null)->get())->pluck('image')->toArray();
        $second_color_image=collect($product->images->where('id','!=', $product_color->id ?? 0)->pluck('image'))->toArray();
        $all_image[]=$product_color->image ?? null;
        $count_image=count($all_image);
        $data = [
            'keys'=>$final_data['keys'],
            'price'=>$price,
            'original_price'=>$original_price,
            'offer_price'=>$offer_price,
            'special_price'=>$specail_price,
            'html_string'=>$html_string,
            'stock_wise_price'=>$stock_wise_price,
            'main_image'=>$product_color->image ?? null,
            'color_image'=>array_merge($color_image, $second_color_image),
            'all_image'=>$all_image,
            'count'=>$count_image-1,
            'stock_qty'=>$stocks[0]->quantity ?? 0,
            'stock_varient_id'=>$stocks[0]->id ?? 0
        ];
        return $data;
    }
    public function getPrice($key, $product_id, $value){
        $stock_way = StockWays::where('key', $key)->where('value', $value)->first();
        // $productStock =
    }
    public function getData($product)
    {
        $stock_ways = collect($product->stockways)->groupBy('stock_id')->toArray();
        $stocks = $product->stocks;
        $paired_values = [];
        $keys = [];
        $colors = [];
        $color = [];
        $price = 0;
        $prices = [];
        $first_available = [];
        foreach($stock_ways as $index=>$stock_way){
            foreach($stock_way as $way){
                $categoryAttribute = CategoryAttribute::find($way['key']);
                $key = [
                    'id'=>$way['key'],
                    'title'=>($categoryAttribute) ? $categoryAttribute->title : "Null",
                ];
                $new_price[$way['id']] = [
                ];
                $keys[] = $key;
                $paired_values[$way['key']][] = $way['value'];
                // if(array_key_first($stock_ways) == $index){
                //     $first_available[$way['key']] = $way;
                // }
            }
        }
        foreach($stocks as $index=>$stock){
            $color = [
                'id'=>$stock->againColor->id,
                'title'=> $stock->againColor->title,
            ];
            $colors[] = $color;
            $color = $stock->againColor->id;
            if(array_key_first(collect($stocks)->toArray()) == $index){
                $price = $stock->price;
            }
        }
        $refine_paired_values = collect($paired_values)->map(function($row, $index){
            return collect($row)->sort()->unique()->toArray();
        })->unique()->toArray();
        $colors = collect($colors)->unique()->toArray();
        $keys = collect($keys)->unique()->toArray();
        $final_data = [
            'keys'=>$keys,
            'values'=>$refine_paired_values,
            // 'first_available'=>$first_available,
            'colors'=>collect($colors)->sort()->toArray(),
            'color'=>$color,
            'price'=>$price
        ];
        return $final_data;
    }
    private function getHtmlPrice()
    {
        $price = 0;
        return $price;
    }
}