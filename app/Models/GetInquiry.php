<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GetInquiry extends Model
{
    use HasFactory;
    protected $fillable=[
        'full_name',
        'email',
        'phone',
        'message',
        'product_id'
    ];

    public function product(){
        return $this->hasOne(Product::class,'id','product_id');
    }
}
