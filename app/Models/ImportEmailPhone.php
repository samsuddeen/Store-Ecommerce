<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportEmailPhone extends Model
{
    use HasFactory;

    protected $fillable = ['phone','email'];

}


