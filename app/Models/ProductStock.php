<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductStock extends Model
{
    use HasFactory;
    protected $guarded = [];
    /**
     * Get the product that owns the ProductStock
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id')->withDefault();
    }

    public function price():Attribute{
        $user=auth()->guard('customer')->user();

        if($user && $user->wholeseller=='1')
        {
            $retailerPrice=$this->checkRetailerOffer();
            return new Attribute(
                get:fn($value)=>($retailerPrice !=0 || $retailerPrice !=null) ? ($this->wholesaleprice-$retailerPrice) : $this->wholesaleprice
            );
        }
        else
        {
            return new Attribute(
                get:fn($value)=>$value
            );
        }
    }

    public function specialPrice():Attribute{
        $user=auth()->guard('customer')->user();
        if($user && $user->wholeseller=='1')
        {
            return new Attribute(
                get:fn($value)=>0
            );
        }
        else
        {
            return new Attribute(
                get:fn($value)=>$value
            );
        }
       
    }

    public function checkRetailerOffer(){
        $offerPrice=0;
        $wholeSeller=auth()->guard('customer')->user();
        $retailerOfferList=RetailerOfferRetailerList::where('retailer_id',$wholeSeller->id)->get();
        $retailerProductList=RetailerOfferProductList::whereIn('retailer_offer_id',$retailerOfferList->pluck('retailer_offer_id')->toArray())->where('product_id',$this->product_id)->get();
        if($retailerProductList && count($retailerProductList) > 0){
            foreach($retailerProductList as $list){
                if($list->retailerOfferSection->offer > $offerPrice){
                    $offerPrice=$list->retailerOfferSection->offer;
                }
            }
        }
        return $offerPrice;
    }

    public static function initalizeStocks(Product $product)
    {
        $sizes = $product->sizes()->pluck('id');
        $colors =  $product->colors()->pluck('color_id');
        foreach ($sizes as $key => $size) {
            foreach ($colors as $k => $color) {
                Self::create(
                    [
                        'product_size_id' => $size,
                        'color_id' => $color,
                        'stock' => 0,
                        'product_id' => $product->id
                    ]
                );
            }
        }
    }
    public function stock_ways()
    {
        return $this->belongsToMany(CategoryAttribute::class, 'stock_ways', 'id', 'key')->withPivot('key', 'value');
    }
    public function stockWays()
    {
        return $this->hasMany(StockWays::class, 'stock_id');
    }

    public function color()
    {
        return $this->hasMany(Color::class,'id','color_id');
    }


    public function againColor()
    {
        return $this->belongsTo(Color::class,'color_id')->withDefault();
    }

    public function colors()
    {
        return $this->hasMany('App\Models\Color','id','color_id');
    }

    public function Image()
    {
        return $this->hasMany('App\Models\ProductImage','color_id','color_id');
    }

    public function getStock()
    {
        return $this->hasMany(StockWays::class,'stock_id','id');
    }

    public function getColor()
    {
        return $this->hasOne(Color::class,'id','color_id');
    }    
}
