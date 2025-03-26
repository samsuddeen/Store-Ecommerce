<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserShareList extends Model
{
    use HasFactory;
    protected $fillable=[
        'share_from',
        'share_to',
        'points',
        'referal_code'
        
    ];
}
