<?php

namespace App\Models\Setting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialSetting extends Model
{
    use HasFactory;
    protected $fillable=[
        'title',
        'slug',
        'url',
        'status',
    ];
}
