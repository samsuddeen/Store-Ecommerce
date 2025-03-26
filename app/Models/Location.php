<?php

namespace App\Models;

use App\Models\Admin\Hub\HubNearPlace;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Location extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable=[
        'district_id',
        'local_id',
        'title',
        'zip_code',
        'user_id',
        'slug',
        'publishStatus'
    ];
    public function local():BelongsTo
    {
        return $this->belongsTo(Local::class, 'local_id')->withDefault();
    }
    public function nearPlace(): HasOne
    {
        return $this->hasOne(HubNearPlace::class, 'location_id');
    }

    public function deliveryRoute():HasOne
    {
        return $this->hasOne(DeliveryRoute::class, 'location_id')->withDefault();
    }
    

}
