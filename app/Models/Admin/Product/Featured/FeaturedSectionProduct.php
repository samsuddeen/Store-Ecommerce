<?php

namespace App\Models\Admin\Product\Featured;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class FeaturedSectionProduct extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table='featured_section_product';
    protected $fillable=[
        'product_id',
        'featured_section_id',
    ];
}
