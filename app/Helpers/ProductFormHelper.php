<?php
namespace App\Helpers;

use App\Models\Product;
use App\Models\ProductImage;

class ProductFormHelper
{
    public static function getFeaturedImage(Product $product)
    {
        $productImage = ProductImage::where('product_id', $product->id)->where('is_featured', true)->get();
        $images=[];
        foreach($productImage as $img){
            array_push($images, $img->image);
        }
        return implode(",", $images);
    }
    public static function getImages(Product $product, $color_id)
    {
        $productImage = ProductImage::where('product_id', $product->id)->where('color_id', $color_id)->where('is_featured', false)->get();
        $images=[];
        foreach($productImage as $img){
            array_push($images, $img->image);
        }
        return implode(",", $images);
    }
    public static function getDangerousGoods(Product $product)
    {
        $dangerous = $product->dangerousGoods;
        $dangerous_goods = [];
        foreach($dangerous as $good){
            array_push($dangerous_goods, $good->dangerous_good);
        }
        return $dangerous_goods;
    }
}