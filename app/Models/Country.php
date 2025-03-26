<?php

namespace App\Models;

use App\Models\Local;
use App\Models\District;
use App\Models\Province;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Country extends Model
{
    use HasFactory;
    protected $fillable = ['name'];
    public function province(){
        return $this->hasMany(Province::class,'country_id')->withDefault();
    }
    public function cities(){
        return $this->hasMany(District::class,'country_id')->withDefault();
    }
    public function areas(){
        return $this->hasMany(Local::class,'country_id')->withDefault();
    }

}
