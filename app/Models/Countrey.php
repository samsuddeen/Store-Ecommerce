<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Countrey extends Model
{
    use HasFactory;
    protected $fillable=[
        'name',
        'country_slug',
        'iso_2',
        'code',
        'status',
        'flags',
        'country_zip'
    ];
}
