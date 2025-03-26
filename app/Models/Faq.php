<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasFactory;

    protected $table = "faqs";

    protected $fillable = [
        'title',
        'slug',
        'description',
        'status',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];
}
