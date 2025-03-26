<?php

namespace App\Models\Payout;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayoutStatus extends Model
{
    use HasFactory;
    protected $fillable=[
        'updated_by',
        'payout_id',
        'status',
        'date',
        'remarks',
    ];
    
    public function Payout()
    {
        return $this->belongsTo(Payout::class,'payout_id')->withDefault();
    }
}