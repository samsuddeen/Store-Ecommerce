<?php

namespace App\Http\Resources;

use App\Data\Color\ColorData;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    // protected $product;
    //  public function __construct($product)
    //  {
    //     $this->product=$product;
    //  }
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "slug" => $this->slug,
            "total_sell" => $this->total_sell,
            "short_description" => $this->short_description,
            "long_description" => $this->long_description,
            "min_order" => $this->min_order,
            "returnable_time" => $this->returnable_time,
            "delivery_time" => $this->delivery_time,
            "keyword" => $this->keyword,
            "package_weight" => $this->package_weight,
            "dimension_length" => $this->dimension_length,
            "dimension_width" => $this->dimension_width,
            "dimension_height" => $this->dimension_height,
            "warranty_type" => $this->warranty_type,
            "warranty_period" => $this->warranty_period,
            "warranty_policy" => $this->warranty_policy,
            "brand_id" => $this->brand_id,
            "country_id" => $this->country_id,
            "category_id" => $this->category_id,
            "rating" => $this->rating,
            "publishStatus" => $this->publishStatus,
            "url" => $this->url,
            // for the top offer 
            "color_id" => (new ColorData())->getColorTitle($this->stocks[0]->color_id),
            'price' => number_format($this->stocks[0]->price),
            "special_price" => number_format(getOfferProduct($this, $this->stocks[0]) ?? $this->stocks[0]->special_price),
            'image' => $this->productImages[0]->image ?? null,
            'varient' => $this->stocks[0]->id,
            'percent'=>apigetDiscountPercnet($this->id) ?? null,
            'created_at'=>$this->created_at,
            'product_tag' => $this->productTags[0]->slug ?? null,
        ];
    }
}
