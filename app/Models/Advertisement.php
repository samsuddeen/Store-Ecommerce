<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Advertisement extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'title',
        'url',
        'image',
        'mobile_image',
        'size',
        'status',
        'ad_type'
    ];

    /**
     * The positions that belong to the Advertisement
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function positions(): BelongsToMany
    {
        return $this->belongsToMany(Position::class, 'advertisement_positions', 'advertisement_id', 'position_id');
    }
}
