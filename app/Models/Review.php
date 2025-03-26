<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Review extends Model
{
    use HasFactory;
    public $fillable=['product_id','user_id','rating','message','attributes','parent_id','seller_id','response','image','status'];
    // protected $primaryKey='product_id';

    protected $casts = [
        'image'
    ];

    public function user()
    {
        return $this->belongsTo(New_Customer::class,'user_id');
    }
    public function product()
    {
        return $this->belongsTo(Product::class,'product_id');
    }

    public function reply()
    {
        return $this->hasMany(Review::class,'parent_id','id')->select('message');
    }

    public function getReview()
    {   
        return $this->hasMany(Review::class,'product_id','product_id');
    }

    public function getReviewReply()
    {
        return $this->hasMany(ReviewReply::class,'review_id','id');
    }
}
