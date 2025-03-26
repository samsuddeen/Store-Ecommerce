<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttributeCategory extends Model
{
    use HasFactory;

    protected $table = "attribute_categories";

    protected $fillable = [
        'title',
        'slug',
        'publish',
    ];

    public function attrValues()
    {
        return $this->hasMany(AttributeValue::class,'att_category_id');
    }
}
