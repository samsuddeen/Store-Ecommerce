<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    protected $table = "cities";

    protected $fillable = [
        'city_name',
        'district_id',
        'latitude',
        'longitude',
    ];

    public function district()
    {
        return $this->belongsTo(District::class, 'district_id', 'id');
    }

    public function getRouteCharge()
    {
        return $this->hasMany(Location::class, 'local_id', 'id')->where('publishStatus', '1')->select(['id', 'local_id', 'title', 'slug', 'image','zip_code']);
    }
}
