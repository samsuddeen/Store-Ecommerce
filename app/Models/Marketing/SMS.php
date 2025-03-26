<?php

namespace App\Models\Marketing;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SMS extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable=[
        'created_by',
        'title',
        'slug',
        'content',
        'status',
        'for',
        'selection',
        'pushed_date',
        'url',
    ];
}
