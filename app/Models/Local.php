<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Local extends Model
{
    use HasFactory;
    protected $fillable = ['local_level_id', 'province_id', 'local_id', 'local_name', 'dist_id', 'country_id'];

    // public function country(){
    //     return $this->belongsTo(Country::class,'country_id');
    // }

    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id');
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'dist_id');
    }

    public function getRouteCharge()
    {
        return $this->hasMany(Location::class, 'local_id', 'id')->where('publishStatus', '1')->select(['id', 'local_id', 'title', 'slug', 'image','zip_code']);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_cities', 'city_id', 'product_id');
    }
}
