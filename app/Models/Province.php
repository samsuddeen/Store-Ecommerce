<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    use HasFactory;
    protected $fillable =['eng_name','status','country_id'];

    // public function country(){
    //     return $this->belongsTo(Country::class,'country_id');
    // }
    public function districts(){
        return $this->hasMany(District::class,'province');
    }

    public function localsit(){
        return $this->belongsTo(Local::class,'dist_id');
    }
    
}
