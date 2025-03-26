<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductStockBack extends Model
{
    use HasFactory;
    protected $guarded = [];

    /**
     * Get the product that owns the ProductStock
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public static function initalizeStocks(Product $product)
    {
        $sizes = $product->sizes()->pluck('id');
        $colors =  $product->colors()->pluck('color_id');
        foreach ($sizes as $key => $size) {
            foreach ($colors as $k => $color) {
                Self::create(
                    [
                        'product_size_id' => $size,
                        'color_id' => $color,
                        'stock' => 0,
                        'product_id' => $product->id
                    ]
                );
            }
        }
    }
}
