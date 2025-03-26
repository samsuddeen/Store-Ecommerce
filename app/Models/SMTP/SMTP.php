<?php

namespace App\Models\SMTP;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SMTP extends Model
{
    use HasFactory;
    protected $fillable=[
        'user_id',
        'mail_user_name',
        'mail_driver',
        'mail_host',
        'mail_port',
        'mail_password',
        'mail_from_address',
        'mail_from_name',
        'mail_encryption',
        'is_default',
    ];
    protected $hidden=[
        'mail_password'
    ];
}
