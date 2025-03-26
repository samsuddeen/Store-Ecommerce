<?php
namespace App\Observers\Category;

use App\Models\Product;
use App\Models\Category;
use App\Models\StockWays;
use App\Models\CategoryAttribute;
use App\Models\ProductAttribute;
use Illuminate\Support\Facades\DB;

class CategoryObserver
{
    protected $category;
    protected $categoryAttribute;
    function __construct(Category $category, CategoryAttribute $categoryAttribute)
    {
        $this->categoryAttribute = $categoryAttribute;
        $this->category = $category;
    }
    public function observe()
    {
        $products = Product::where('category_id', $this->category->id)->get();
        $stock_ways = [];
        $product_attributes = [];
        if(count($products) > 0){
            foreach($products as $product){
                foreach($product->stocks ?? [] as $stock){
                    $stock_ways[] = StockWays::where('stock_id', $stock->id ?? null)->where('key', $this->categoryAttribute->id ?? null)->get();
                }
                $product_attributes[] = ProductAttribute::where('key', $this->categoryAttribute->id ?? null)->where('product_id', $product->id ?? null)->get();
            }
        }
        $product_attributes = collect(collect($product_attributes)->flatten()->toArray())->pluck('id')->toArray();
        $final_stock_ways = collect(collect($stock_ways)->flatten()->toArray())->pluck('id')->toArray();
        if(count($final_stock_ways) > 0){
            DB::table('stock_ways')->whereIn('id',  $final_stock_ways)->delete();
        }
        if(count($product_attributes) > 0){
            DB::table('product_attributes')->whereIn('id',  $product_attributes)->delete();
        }
        $this->categoryAttribute->delete();
    }
}