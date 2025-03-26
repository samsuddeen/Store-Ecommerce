<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RewardSection extends Model
{
    use HasFactory;
    protected $fillable=[
        'title',
        'from',
        'to',
        'status',
        'points'
    ];
}
