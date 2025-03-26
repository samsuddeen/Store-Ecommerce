<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    public $primarykey=['user_id'];

    protected $fillable=[
        'user_id',
        'total_price',
        'total_qty',
        'total_discount',
        'additional_charge',
    ];

    public function productImage()
    {

        return $this->hasManyThrough(ProductImage::class,Product::class,'product_id');
    }

    public function getProduct()
    {
        return $this->hasOne(Product::class,'id','product_id');
    }

    public function user()
    {
        return $this->belongsTo(New_Customer::class,'user_id');
    }


    public function cartAssets(){
        return $this->hasMany(CartAssets::class,'cart_id');
    }
    
    public function cartStock(){
        return $this->hasMany(CartStock::class,'cart_id');
    }

    public function getCartAsset()
    {
        return $this->hasMany(CartAssets::class,'cart_id','id');
    }
}


