<?php

namespace App\Models\Admin\Product\Featured;

use App\Http\Requests\FeatureSectionUpdateRequest;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FeaturedSection extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable=[
        'id',
        'title',
        'slug',
        'status',
        'type',
        'meta_keywords',
        'meta_Description',
        'meta_title',
    ];

    
    public function product()
    {
        return $this->belongsToMany(Product::class, 'featured_section_product', 'featured_section_id', 'product_id');
    }

    public function featuredProducts()
    {
        return $this->belongsToMany(Product::class)->withPivot('product_id');
    }
    public function featured()
    {
        return $this->hasMany(FeaturedSectionProduct::class, 'featured_section_id');
    }

    public function getCategory(){
        return $this->hasMany(Category::class,'id','foreign_id');
    }

    public function getBrand(){
        return $this->hasMany(Brand::class,'id','foreign_id');
    }

    public function getProduct(){
        return $this->hasMany(Product::class,'id','foreign_id');
    }

    public function getFeatured(){
        return $this->hasMany(FeaturedSectionProduct::class,'featured_section_id','id');
    }

   

}
