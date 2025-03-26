<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class footertitle extends Model
{
    use HasFactory;
    protected $table="footertitles";
    protected $fillable=['title','status'];
}
