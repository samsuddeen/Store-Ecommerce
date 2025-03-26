<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WholeSeller extends Model
{
    use HasFactory;
    protected $fillable=[
        'name',
        'email',
        'phone',
        'address',
        'status',
        'photo',
        'province',
        'district',
        'area',
        'zip',
        'company_name',
        'verify_token',
        'verify_otp',
        'email_verified_at',
        'password',

        // 'social_provider',

        
       
        // 'provider_id',
        // 'fb_id',
        // 'google_id',
        // 'socialite',
        // 'user_id',
        // 'social_avatar',
        // 'referal_code',
        // 'fb_status'
    ];
    protected $casts=[
        'status'=>\App\Enum\WholeSeller\WholeSellerStatusEnum::class
    ];
}
