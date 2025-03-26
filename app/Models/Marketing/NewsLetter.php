<?php

namespace App\Models\Marketing;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NewsLetter extends Model
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
        'email_selection',
        'pushed_date',
        'url',
        'phone_selection',
    ];
}
