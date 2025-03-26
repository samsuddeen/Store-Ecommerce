<?php

namespace App\Models\Setting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seo extends Model
{
    use HasFactory;
    protected $fillable=[
        'meta_title',
        'meta_keywords',
        'meta_description',
        'og_image',
    ];
}
