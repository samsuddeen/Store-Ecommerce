<?php

namespace App\Models;

use App\Models\Admin\Offer\Product\TopOfferProduct;
use App\Models\Review;
use App\Models\ProductStock;
use App\Models\Local;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Admin\Product\Featured\FeaturedSection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\seller as Seller;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'policy_data',
        'is_new',
        'name',
        'slug',
        'vat_percent',
        'short_description',
        'long_description',
        'on_sale',
        'new_arrival',
        'top_ranked',
        'min_order',
        'returnable_time',
        'return_policy',
        'delivery_time',
        'warranty_type',
        'warranty_period',
        'warranty_policy',
        'package_weight',
        'dimension_length',
        'dimension_width',
        'dimension_height',
        'keyword',
        'brand_id',
        'country_id',
        'category_id',
        'user_id',
        'rating',
        'publishStatus',
        'status',
        'url',
        'total_sell',
        'seller_id',

        // SEO
        'meta_title',
        'meta_keywords',
        'meta_description',
        'og_image',
        'shipping_charge',
        'product_for'
    ];

    


    public $casts = [
        'publishStatus' => 'boolean'
    ];
    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class, 'seller_id')->withDefault();
    }
    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class, 'product_id');
    }
    public function featured_image(): string
    {
        
        return $this->images[0]->image ?? '';
        
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id')->withDefault();
    }

    public function features(): HasMany
    {
        return $this->hasMany(ProductFeature::class, 'product_id');
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class, 'brand_id')->withDefault();
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

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id', 'id');
    }

    // public function stocks(): HasMany
    // {
    //     return $this->hasMany(ProductStock::class, 'product_id');
    // }

    /**
     * Get all of the stockways for the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function stockways(): HasManyThrough
    {
        return $this->hasManyThrough(StockWays::class, ProductStock::class, 'product_id', 'stock_id');
    }
    /**
     * Scope a query to only include active users.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return void
     */
    // public function scopeVisible($query)
    // {
    //     $query->where('user_id', auth()->id());
    // }
    public function scopeVisible($query)
    {
        $query->where('seller_id', auth()->guard('seller')->id());
    }

    public function productTags()
    {
        return $this->belongsToMany(Tag::class)->withPivot('product_id', 'tag_id');
    }
    public function attributes()
    {
        return $this->belongsToMany(CategoryAttribute::class, 'product_attributes', 'product_id', 'key');
    }
    public function attributessss()
    {
        // return $this->belongsToMany(CategoryAttribute::class, 'product_attributes', 'key', 'product_id');
        return $this->hasMany(ProductAttribute::class, 'product_id', 'id');
    }
    public function dangerousGoods()
    {
        return $this->hasMany(ProductDangerous::class, 'product_id');
    }
    public function reviews()
    {
        return $this->hasMany(Review::class, 'product_id')->orderBy('id', 'DESC');
    }
    public function featuredSections()
    {
        return $this->belongsToMany(FeaturedSection::class)->withPivot('featured_section_id');
    }
    public function getPrice()
    {
        return $this->hasOne(ProductStock::class, 'product_id', 'id');
    }
    public function stocks()
    {
        return $this->hasMany(ProductStock::class, 'product_id', 'id');
    }
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
    public function wishList()
    {
        return $this->hasOne(WishList::class, 'product_id', 'id');
    }
    public function iswish()
    {
        return $this->hasOne(WishList::class, 'product_id', 'id');
    }
    public function orderAssets()
    {
        return $this->hasMany(orderAssets::class, 'product_id');
    }
    public function comments()
    {
        return $this->hasMany(QuestionAnswer::class, 'product_id')->orderBy('id', 'DESC');
    }
    public function productCategory(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id')->select(['id', 'title', 'slug', 'image', 'showOnHome', 'parent_id'])->withDefault();
    }
    public function productImages()
    {
        return $this->hasMany(ProductImage::class, 'product_id', 'id');
    }

    public function getOfferProduct()
    {
        return $this->hasMany(TopOfferProduct::class, 'product_id', 'id');
    }

    public function getTotalStock(): int
    {
        $qty = 0;
        foreach ($this->stocks ?? [] as $stock) {
            $qty = $qty + $stock->quantity;
        }
        return $qty;
    }

    public function city()
    {
        return $this->belongsToMany(City::class, 'product_cities', 'product_id', 'city_id');
    }
}
