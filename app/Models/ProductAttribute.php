<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAttribute extends Model
{
    use HasFactory;
    protected $fillable=[
        'product_id',
        'key',
        'value',
    ];
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function categoryAttribute()
    {
        return $this->hasOne(CategoryAttribute::class,'id','key');
    }
}
