<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
    use SoftDeletes;
    use HasFactory;
    protected $fillable = ['name', 'logo','status','slug'];

    public function getProduct()
    {
        return $this->hasMany(Product::class,'brand_id','id')->select(['id','name','slug','short_description','long_description','brand_id','rating']);
    }
}
