<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Color extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['title', 'colorCode','status'];
    public function productImages()
    {
        return $this->hasMany(ProductImage::class);
    }
    
    public function productStock(){
        return $this->hasOne(ProductStock::class,'color_id');
    }
    
    public function stock()
    {
     return $this->hasOne(ProductStock::class, 'color_id');
    }

    public function getColorProduct()
    {
        return $this->hasMany(ProductStock::class,'color_id','id');
    }

    
}
