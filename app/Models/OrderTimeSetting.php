<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderTimeSetting extends Model
{
    use HasFactory;

    protected $table = "order_time_settings";

    protected $fillable = [
        'day',
        'start_time',
        'end_time',
        'day_off',
    ];
}
