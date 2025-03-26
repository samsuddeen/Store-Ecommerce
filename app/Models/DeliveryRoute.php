<?php

namespace App\Models;

use App\Models\Admin\Hub\Hub;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeliveryRoute extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable=[
        'user_id',
        'hub_id',
        'location_id',
        'local_id',
        'is_location',
        'charge',
    ];

    public function from() : BelongsTo
    {
        return $this->belongsTo(Hub::class, 'hub_id');
    }
    public function to(): BelongsTo
    {
        if($this->is_location) {
            return $this->belongsTo(Location::class, 'location_id')->withDefault();
        }else{
            return $this->belongsTo(Local::class, 'local_id')->withDefault();
        }
    }
}
