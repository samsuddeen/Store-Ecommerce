<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GenerateOtp extends Model
{
    use HasFactory;

    protected $fillable=[
        'email',
        'otp'
    ];
}
