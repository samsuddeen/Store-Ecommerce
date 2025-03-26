<?php

namespace App\Actions\Product;

use App\Models\Color;
use App\Models\Product;
use App\Models\Tag;

class ProductFormActionBack
{
    public function __construct(
        public Product $product
    ) {
    }
    public function getData(): array
    {
        return [
            'product' => $this->product,
            'features' => $this->getFeatures(),
            'images' => $this->getImages(),
            'colors' => $this->getColors(),
            'selectedTags' => $this->getProductTags(),
            'allColor' => $this->getAllColors(),
            'tags' => $this->getAllTags(),
            'sizes' => $this->getSizes()
        ];
    }

    private function getProductTags(): array
    {
        return $this->product->productTags()->pluck('tags.id')->toArray();
    }

    private function getColors(): array
    {
        return $this->product->colors()->pluck('colors.id')->toArray();
    }

    private function getSizes()
    {
        return $this->product->sizes()->select('size')->get();
    }

    private function getFeatures()
    {
        return  $this->product->features()->select('feature as name')->get();
    }

    private function getImages()
    {
        $images =  $this->product->images()->pluck('image')->toArray();
        return implode(',', $images);
    }

    private function getAllColors()
    {
        return  Color::get();
    }

    private function getAllTags()
    {
        return  Tag::where('publishStatus', true)->get();
    }
}
