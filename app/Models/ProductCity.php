<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use App\Models\Local;

class ProductCity extends Model
{
    use HasFactory;

    protected $table = "product_cities";

    protected $fillable = [
        'product_id',
        'city_id'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class,'product_id');
    }

    public function cities()
    {
        return $this->belongsToMany(City::class,'city_id');
    }
}
