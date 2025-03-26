<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CurrencyExchange extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable=[
        'currency_id',
        'buy',
        'sell',
        'date',
    ];
    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }
}
