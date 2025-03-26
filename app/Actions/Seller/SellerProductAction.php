<?php

namespace App\Actions\Seller;

use App\Models\Brand;
use App\Models\Color;
use App\Models\Product;
use App\Models\Tag;

class SellerProductAction
{
    public function __construct(
        public Product $product
    ) {
    }
    public function getData(): array
    {
        $data =  [
            'product' => $this->product,
            'colors' => $this->getAllColors(),
            'brands' => $this->getBrands(),
            'tags' => $this->getAllTags(),
            'selectedTags' => $this->getProductTags(),
        ];
        if (!empty($this->product->category)) {
            $data['attributes'] = $this->getAttributes();
        }
        if (!empty($this->product->images())) {
            $data['color_id'] = $this->getColorId();
        }
        if (!empty($this->product->attributes())) {
            $data['productAttributes'] = $this->getProductAttribute();
        }
        if (!empty($this->product->images)) {
            $data['images'] = $this->getImages();
        }
        return $data;
    }
    private function getImages()
    {
        return $this->product->images();
    }

    private function getProductTags(): array
    {
        return $this->product->productTags()->pluck('tags.id')->toArray();
    }

    private function getColorId()
    {
        $colorr_id = [];
        foreach ($this->product->images as $image) {
            if ($image->color_id !== null) {
                array_push($colorr_id, $image->color_id);
            }
        }
        $color_id_again = array_unique($colorr_id);
        return $color_id_again;
    }
    private function getAttributes()
    {
        $attributes = $this->product->category->attributes;
        return $attributes;
    }
    public function getProductAttribute()
    {
        $attributes = $this->product->attributes;
        return $attributes;
    }
    private function getAllColors()
    {
        return  Color::get();
    }
    private function getBrands()
    {
        return Brand::all();
    }
    private function getAllTags()
    {
        return  Tag::where('publishStatus', true)->get();
    }
}
