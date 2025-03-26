<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kalnoy\Nestedset\NodeTrait;

class Category extends Model
{
    use HasFactory;
    use NodeTrait;
    use SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'icon',
        'image', 
        'showOnHome', 
        'parent_id', 
        '_lft', 
        '_rgt',
        'status',


        // seo
        'meta_title',
        'meta_keywords',
        'meta_description',
        'og_image',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'category_id');
    }

    /**
     * Get all of the attributes for the Category
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attributes(): HasMany
    {
        return $this->hasMany(CategoryAttribute::class, 'category_id');
    }

    public function subcat()
    {

        return $this->hasMany($this::class,'parent_id','id');
    }



    public function getProduct(): HasMany
    {
        return $this->hasMany(Product::class, 'category_id');
    }

    public function getParent()
    {
        return $this->hasOne(Category::class,'id','parent_id');
    }
}
