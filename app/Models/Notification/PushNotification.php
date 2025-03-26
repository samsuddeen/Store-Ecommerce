<?php

namespace App\Models\Notification;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PushNotification extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable=[
        'created_by',
        'title',
        'slug',
        'image',
        'summary',
        'description',
        'status',
        'for',
        'selection',
        'pushed_date',
        'url',
    ];
}
