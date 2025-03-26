<?php

namespace App\Actions\Product;

use App\Models\Product;

class ProductAttributesActionBack
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
        $this->syncFeatures();
        $this->syncImages();
        $this->syncColors();
        $this->syncTags();
        $this->syncSizes();
    }

    private function syncFeatures()
    {
        $this->product->features()->delete();
        $this->product->features()->createMany(collect($this->data['features'])->map(fn ($item, $index) => ['feature' => $item])->toArray());
    }

    private function syncImages()
    {
        $this->product->images()->delete();
        $this->product->images()->createMany(collect($this->data['images'])->map(fn ($item, $index) => ['image' => $item])->toArray());
    }

    private function syncColors()
    {
        $this->product->colors()->sync($this->data['colors']);
    }

    private function syncTags()
    {
        $this->product->productTags()->sync($this->data['tags']);
    }

    private function syncSizes()
    {
        $this->product->sizes()->delete();
        $this->product->sizes()->createMany(collect($this->data['sizes'])->map(fn ($item, $index) => ['size' => $item])->toArray());
    }
}
