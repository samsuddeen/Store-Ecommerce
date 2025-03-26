<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kalnoy\Nestedset\NodeTrait;

class Menu extends Model
{
    use HasFactory;
    use NodeTrait;
    use SoftDeletes;
    
    protected $fillable = [
        'name',
        'slug',

        'model',
        'model_id',

        'position',

        'banner_image',
        'image',
        'status',
        'content',
        'external_link',


        'meta_title',
        'meta_keywords',
        'meta_description',
        'og_image',

        'parent_id', 
        '_lft', 
        '_rgt',

        'menu_type',
        'show_on',


    ];

    public function category()
    {
        return $this->belongsTo(MenuCategory::class, 'category_slug', 'slug');
    }


    // public function children()
    // {
    //     return $this->hasMany(Menu::class, 'parent_id');
    // }


    // newly developed
    public function subMenu()
    {

        return $this->hasMany($this::class,'parent_id','id');
    }


    public function getParent()
    {
        return $this->hasOne($this::class,'id','parent_id');
    }
}
