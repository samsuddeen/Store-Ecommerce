<?php

namespace App\Models\Backup;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Backup extends Model
{
    use HasFactory;
    protected $fillable=[
            'created_by',
            'file_name',
    ];

}
