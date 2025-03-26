<?php

namespace App\Actions\Product\Image;

use App\Models\Product;
use Illuminate\Support\Arr;
use App\Models\ProductImage;

class ProductImageAction
{
    protected $product;
    protected $data;
    function __construct(Product $product, $data)
    {
        $this->product = $product;
        $this->data = $data;
    }
    public function handle()
    {
        return $this->syncImageWithColor();
    }
    private function syncImageWithColor()
    {
        if (Arr::get($this->data, 'image_name')) {
            if (count($this->data['image_name'])) {
                $this->product->images()->delete();
                $images = $this->data['image_name'];
                array_pop($images);
                array_shift($images);
                $featuredImages = $this->data['image_name'][0];
                $featuredImages = explode(',', $featuredImages);
                foreach ($featuredImages as $image) {
                    $this->product->images()->create([
                        'image' => $image,
                        'is_featured' => true,
                    ]);
                }
                foreach ($images as $key => $value) {
                    $imageCache = explode(',', $value);
                    foreach ($imageCache as $image) {
                        $this->product->images()->create([
                            'color_id' => $this->data['color'][$key],
                            'image' => $image,
                        ]);
                    }
                }
            }
        }
    }
    public function updateImageWithColor()
    {
        $productImageData=$this->product->productImages->where('is_featured',false);
        foreach($productImageData as $data){
            $data->delete();
        }
        $productImageInsert=[];
        if (Arr::get($this->data, 'image_name')) {
            if (count($this->data['image_name'])) {
                $images = $this->data['image_name'];
                foreach ($images as $key => $value) {
                    $imageCache = explode(',', $value);
                   
                    foreach ($imageCache as $image) {
                        $productImageInsert[]=[
                            'product_id'=>$this->product->id,
                            'image'=>$image,
                            'color_id'=>$this->data['image_color'][$key],
                            'is_featured'=>false
                        ];
                        // $productImage = ProductImage::where([
                        //     'product_id'=>$this->product->id,
                        //     'image'=>$image,
                        //     'color_id'=>$this->data['image_color'][$key],
                        //     'is_featured'=>false
                        // ])->first();
                        // if(!$productImage){
                        //     ProductImage::updateOrCreate([ 
                        //         'image' => $image,
                        //         'product_id'=>$this->product->id,
                        //         'color_id' => $this->data['image_color'][$key],
                        //         'is_featured'=>false,
                        //     ]);
                        // }
                    }
                    
                }
                ProductImage::insert($productImageInsert);
                // dd($productImageInsert);
            }
        }
    }
}
