<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellerCommission extends Model
{
    use HasFactory;
    protected $fillable=[
        'title',
        'percent',
        'status'
    ];

    public function category()
    {
        return $this->hasMany(SellerCommissionCategory::class,'seller_commisssion_id','id');
    }

    public function brand()
    {
        return $this->hasMany(SellerCommissionBrand::class,'seller_commisssion_id','id');
    }
}
