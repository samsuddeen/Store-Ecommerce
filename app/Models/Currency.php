<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;
    protected $fillable=[
        'user_id',
        'iso3',
        'name',
        'unit',
        'flag',
    ];  


    public function exchangesRates()
    {
        return $this->hasMany(CurrencyExchange::class, 'currency_id');
    }

}
