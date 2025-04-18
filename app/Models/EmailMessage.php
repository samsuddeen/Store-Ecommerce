<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailMessage extends Model
{
    use HasFactory;
    protected $fillable=[
        'message',
        'status',
        'footer_message',
        'note'
    ];
}
