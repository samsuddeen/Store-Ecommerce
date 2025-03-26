<?php

namespace App\Models\Admin\Hub;

use App\Models\Local;
use App\Models\Location;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HubNearPlace extends Model
{
    use HasFactory;
    protected $fillable=[
        'hub_id',
        'local_id',
        'location_id',
        'is_location',
    ];
    public function hub(): BelongsTo
    {
        return $this->belongsTo(Hub::class, 'hub_id')->withDefault();
    }
    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'location_id')->withDefault();
    }
    public function local() : BelongsTo
    {
        return $this->belongsTo(Local::class, 'local_id')->withDefault();
    }
}
