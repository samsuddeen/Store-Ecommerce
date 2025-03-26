<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserBillingAddress extends Model
{
    use HasFactory;
    public $primarykey = ['user_id','id'];
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
        'state',
        'random_ref_id',
        'country'
    ];
    public function user(){
        return $this->belongsTo(New_Customer::class,'user_id');
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
