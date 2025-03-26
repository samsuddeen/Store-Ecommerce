<?php

namespace App\Models;

use App\Models\District;
use App\Models\Province;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserShippingAddress extends Model
{
    use HasFactory;

    public $primaryKey='id';

    protected $fillable=[
        'name',
        'user_id',
        'email',
        'phone',
        'province',
        'district',
        'area',
        'additional_address',
        'zip',
        'area_id',
        'state',
        'random_ref_id',
        'country'
    ];

    public function getLocation()
    {
        return $this->hasOne(Location::class,'id','area_id');
    }

    public function getProvince()
    {
        return $this->hasOne(Province::class,'id','province');
    }

    public function getDistrict()
    {
        return $this->hasOne(District::class,'id','district');
    }
}
