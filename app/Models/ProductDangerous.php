<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductDangerous extends Model
{
    use HasFactory;
    protected $fillable=[
        'dangerous_good',
    ];
    
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
