<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CartAssets extends Model
{
    use HasFactory;

    protected $fillable = [
        'cart_id',
        'product_name',
        'product_id',
        'price',
        'qty',
        'sub_total_price',
        'color',
        'image',
        'discount',
        'options',
        'is_ordered',
        'varient_id',
        'vatamountfield',
        'additional_charge'
    ];

    public function cart()
    {
        return $this->belongsTo(Cart::class, "cart_id");
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id')->withDefault();
    }

    public function cartStock()
    {
        return $this->hasMany(CartStock::class, 'cart_assets_id');
    }

    public function cartAssest():HasMany
    {
        return $this->hasMany(CartAssets::class, 'cart_id');
    }

    public function items():HasMany
    {
        return $this->hasMany(CartAssets::class, 'cart_id');
    }

    public function productImage()
    {
        return $this->hasMany(ProductImage::class,'product_id','product_id');
    }
}
