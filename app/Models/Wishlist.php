<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class WishList extends Model
{
    use HasFactory;

    public $primarykey=['product_id','user_id'];

    protected $fillable=[
        'user_id',
        'product_id'
    ];


    public function getProduct()
    {
        return $this->hasOne(Product::class,'id','product_id');
    }

    public function getPrice()
    {
        return $this->hasOneThrough(ProductStock::class,'product_id');
    }



}
