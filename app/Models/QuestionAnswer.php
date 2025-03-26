<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionAnswer extends Model
{
    use HasFactory;
    protected $fillable=[
        'customer_id',
        'seller_id',
        'parent_id',
        'product_id',
        'question_answer',
        'status'
    ];

    public function answer()
    {
        return $this->hasOne($this::class,'parent_id','id');
    }
    
    public function user()
    {
        return $this->hasOne(New_Customer::class,'id','customer_id');
    }
    public function product(){
        return $this->belongsTo(Product::class);
    }
}
