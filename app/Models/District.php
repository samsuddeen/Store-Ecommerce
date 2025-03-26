<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;
    public $primarykey = ['id', 'dist_id',];
    protected $fillable = [
        'province',
        'np_name',
    ];
    public function province()
    {
        return $this->belongsTo(Province::class, 'province');
    }

    public function city()
    {
        return $this->hasMany(City::class, 'district_id');
    }

    // public function local_address()
    // {
    //     return $this->hasMany(Local::class, 'dist_id', 'id');
    // }

    public function localarea()
    {
        return $this->hasMany('App\Models\City', 'district_id', 'id');
    }
}
