<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartAssetDamage extends Model
{
    use HasFactory;

    public $primarykey=['product_id','color'];


    protected $fillable=[
        'cart_id',
        'product_id',
        'product_name',
        'price',
        'qty',
        'sub_total_price',
        'color',
        'discount',
        'options',
        'varient_id'
    ];


    public function productImage()
    {
        return $this->hasMany(ProductImage::class,'product_id','product_id');
    }
}
