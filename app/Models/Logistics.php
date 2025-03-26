<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Logistics extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable=[
        'logistic_name',
        'slug',
    ];

    public function delivery_charge()
    {
        return $this->hasMany(DeliveryCharge::class, 'logistic_id');
    }
}
