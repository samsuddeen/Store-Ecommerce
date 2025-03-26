<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Product;

class Tag extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable=[
        'user_id',
        'title',
        'summary',
        'description',
        'image',
        'thumbnail',
        'slug',
        'order',
        'publishStatus',
        'alt_img'
    ];

    function getThumbs($url=""){
        $base = basename($url);
        if (strpos($url, 'https://') !== false or strpos($url, 'http://') !== false) {
            return str_replace($base, "thumbs/".$base, $url);
        }else{
            $preUrl = "storage/";
            $beforeBase = str_replace($base, "",$url);
            return $preUrl.$beforeBase.'thumbs/'.$base;
        }
    }
    public function products()
    {
        return $this->belongsToMany(Product::class)->where('status',1)->where('publishStatus','1');
    }
}
