<?php

    namespace App\Models\Admin\Hub;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hub extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable=[
        'created_by',
        'title',
        'slug',
        'address',
        'status'
    ];
    public function nearPlace(): HasMany
    {
        return $this->hasMany(HubNearPlace::class, 'hub_id');
    }
}
