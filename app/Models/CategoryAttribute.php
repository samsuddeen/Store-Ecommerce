<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CategoryAttribute extends Model
{
    use HasFactory;
    protected $fillable = [
        'category_id',
        'value',
        'helpText',
        'title',
        'stock'
    ];

    protected $casts = [
        'value' => 'string',
        'stock' => 'boolean'
    ];

    /**
     * Get the category that owns the CategoryAttribute
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    public function stockways(): BelongsTo
    {
        return $this->belongsTo(StockWays::class, 'key');
    }
}
