<?php

namespace App\Models\Refund;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RefundStatus extends Model
{
    use HasFactory;
    protected $fillable=[
        'updated_by',
        'refund_id',
        'status',
        'date',
        'remarks',
    ];
    public function refund():BelongsTo
    {
        return $this->belongsTo(Refund::class, 'refund_id')->withDefault();
    }
}
