<?php
namespace App\Observers\Transaction;

use App\Models\CategoryAttribute;
use App\Models\Order;
use App\Models\ProductStock;
use App\Models\StockWays;

class TransactionObserver
{
    protected $order;
    function __construct(Order $order)
    {
        $this->order = $order;
    }
    public function observe()
    {
       $this->changeStock();
    }
    private function changeStock()
    {
        try {
            foreach($this->order->orderAssets as $row){
                $product = $row->product;
                $final_stock = [];
                if($product){
                    $category = $product->category;
                    if($category){
                        $attributes = CategoryAttribute::where('category_id', $category->id)->get();
                        $checked_attributes = collect($attributes)->map(function($row){
                            return[
                                'id'=>$row->id,
                                'title'=>$row->title,
                                'value'=>explode(',', $row->value ?? null),
                            ];
                        });
                        $stock_ways = [];
                        foreach($checked_attributes as $attribute){
                            $stock_ways[] = StockWays::where('key', $attribute['id'])->whereIn('value', $attribute['value'])->get();
                        }
                        $stock_ways = collect(collect($stock_ways)->unique())->flatten();
                        $stock_ways = collect($stock_ways)->groupBy('stock_id')->toArray();
                        if(count($checked_attributes) > 0){
                            if(count($row->options) > 0){
                                $productStock = ProductStock::where('product_id', $product->id ?? null)->where('color_id', $row->color)->get();
                                if(count($stock_ways) > 0){
                                    $final_stock = collect($stock_ways)->map(function($row, $index) use($productStock){
                                        if(in_array($index, collect($productStock)->pluck('id')->toArray())){
                                            return $index;
                                        }else{
                                            return null;
                                        }
                                    });
                                }
                                $final_stock = collect($final_stock)->whereNotNull();
                            }
                        }
                    }
                }
                if(count($final_stock) > 0){
                    foreach($final_stock as $stock){
                        $stock = ProductStock::find($stock);
                    }
                }
                $stock->update([
                    'quantity'=>$row->qty,
                ]);
            }
        } catch (\Throwable $th) {
           info($th->getMessage());
        }
    }
}