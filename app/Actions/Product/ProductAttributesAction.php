<?php

namespace App\Actions\Product;

use App\Models\Product;
use App\Models\CategoryAttribute;
use Illuminate\Support\Arr;

class ProductAttributesAction
{
    public array $data;
    public Product $product;
    public function __construct(Product $product, array $input)
    {
        $this->data = $input;
        $this->product = $product;
    }

    public function handle()
    {
        $this->syncTags();
        $this->syncAttributes();
        $this->syncStock();
    }
    public function handleSyncAttributes()
    {
        $this->syncAttributes();
    }
    public function handleSyncTags()
    {
        $this->syncTags();
    }
    public function handleSyncStock()
    {
        $this->syncStock();
    }
    private function syncAttributes()
    {
        if (Arr::get($this->data, 'attribute')) {
            $this->product->attributes()->detach();
            // dd($this->data,'sumit');
            foreach ($this->data['attribute'] as $key => $value) {
                $this->product->attributes()->attach($this->product->id, [
                    // 'category_id'=> $this->data['category_id'] ?? ($this->product->category_id ?? null),
                    'key' => $value,
                    'value' => $this->data['value'][$key],
                ]);
            }
            return true;
        }
    }
    public function syncStock($stockway = true)
    {
        // dd($this->data);
        if (Arr::get($this->data, 'price')) {
            $this->product->stockWays()->delete();
            $this->product->stocks()->delete();
            $price = $this->data['price'];
            foreach ($price as $key => $value) {
                $mimqty = $this->data['mimquantity'][$key];
                if ($mimqty != 0) {
                    if ($mimqty > $this->data['quantity'][$key]) {
                        $mimqty = $this->data['quantity'][$key] ?? 1;
                    } else {
                        $mimqty = $mimqty;
                    }
                } else {
                    $mimqty = $this->data['quantity'][$key] ?? 1;
                }
                $stock =  $this->product->stocks()->create([
                    'price' => $value,
                    'color_id' => $this->data['color'][$key] ?? null,
                    'wholesaleprice' => $this->data['wholesaleprice'][$key] ?? $value,
                    'mimquantity' => $mimqty ?? 0,
                    'special_price' => $this->data['special_price'][$key] ?? null,
                    'special_from' => $this->data['special_from'][$key] ?? null,
                    'special_to' => $this->data['special_to'][$key] ?? null,
                    'quantity' => $this->data['quantity'][$key] ?? null,
                    'sellersku' => $this->data['sellersku'][$key] ?? null,
                    'free_items' => $this->data['free_items'][$key] ?? null,
                    'additional_charge' => $this->data['additional_charge'][$key] ?? null,
                ]);
                if ($stockway) {
                    $this->syncStockWays($stock, $key);
                }
            }
        }
    }
    private function syncStockWays($stock, $index)
    {

        if (Arr::get($this->data, 'price')) {
            $count = count($this->data['price']);
            $attributes = CategoryAttribute::where('category_id', $this->data['category_id'])->where('stock', true)->get();
           
            if ($attributes->count() <= 0) {
                return;
            }
            // dd($this->data);
            $keys =  array_chunk($this->data['key'], $attributes->count());
            $values = array_chunk($this->data['attributes'], $attributes->count());
            $saveData = [];
            $showData = [];
            foreach ($keys[$index] as $key => $value) {
                for ($i = 0; $i < $count; $i++) {
                    $saveData = [
                        'stock_id' => $stock->id,
                        'key' => $value,
                        'value' => $values[$index][$key],
                    ];
                }
                array_push($showData, $saveData);
            }
            $stock->stockWays()->createMany($showData);
        }
    }
    private function syncTags()
    {
        $this->product->productTags()->sync($this->data['tags']);
    }
}
