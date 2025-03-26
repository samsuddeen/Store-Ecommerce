<?php

namespace App\Models\Setting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayoutSetting extends Model
{
    use HasFactory;
    protected $fillable=[
        'created_by',
        'period',
        'is_default',
        'status',
    ];
}
