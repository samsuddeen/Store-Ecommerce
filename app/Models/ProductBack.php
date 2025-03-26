<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductBack extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category_id',
        'url',
        'short_description',
        'long_description',
        'warranty_type',
        'warranty_period',
        'warranty_policy',
        'package_weight',
        'dimension_length',
        'dimension_width',
        'dimension_height',
        'rating',
        'publishStatus',
    ];


    public $casts = [
        'publishStatus' => 'boolean'
    ];

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class, 'product_id');
    }
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function features(): HasMany
    {
        return $this->hasMany(ProductFeature::class, 'product_id');
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function colors(): BelongsToMany
    {
        return $this->belongsToMany(Color::class, 'product_colors', 'product_id', 'color_id');
    }

    public function sizes(): HasMany
    {
        return $this->hasMany(ProductSize::class, 'product_id');
    }

    public function productPrice(): Attribute
    {
        return new Attribute(
            get: fn ($value) => $this->currency . " " . $this->price,
        );
    }

    public function stocks(): HasMany
    {
        return $this->hasMany(ProductStock::class, 'product_id');
    }

    /**
     * Scope a query to only include active users.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return void
     */
    public function scopeVisible($query)
    {
        $query->where('user_id', auth()->id());
    }
    public function productTags()
    {
        return $this->belongsToMany(Tag::class)->withPivot('tag_id');
    }
}
