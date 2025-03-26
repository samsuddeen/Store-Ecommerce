<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockWays extends Model
{
    use HasFactory;
    protected $fillable=[
        'stock_id',
        'key',
        'value',
    ];


    public function stock()
    {
        return $this->belongsTo(ProductStock::class, 'stock_id');
    }
    public function categoryAttribute()
    {
        return $this->belongsTo(CategoryAttribute::class, 'key')->withDefault();
    }

    public function category()
    {
        return $this->hasOne(CategoryAttribute::class,'id','key');
    }

    public function getOption()
    {
        return $this->hasOne(CategoryAttribute::class,'id','key');
    }
}
