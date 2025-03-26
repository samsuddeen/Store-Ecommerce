<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttributeValue extends Model
{
    use HasFactory;

    protected $table = "attribute_values";

    protected $fillable = [
        'att_category_id',
        'value',
        'status'
    ];

    public function attrCategory()
    {
        return $this->belongsTo(AttributeCategory::class);
    }
}
