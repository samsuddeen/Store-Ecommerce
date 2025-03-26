<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Position extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'key'];

    /**
     * The advertisements that belong to the Position
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function advertisements(): BelongsToMany
    {
        return $this->belongsToMany(Advertisement::class, 'advertisement_positions', 'advertisement_id', 'position_id');
    }

    public function name(): Attribute
    {
        return new Attribute(
            get: fn ($value) => $this->title . ' (' . $this->key . ' )',
        );
    }
}
