<?php

namespace App\Models\Backup;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BackupPeriod extends Model
{
    use HasFactory;
    protected $fillable=[
        'created_by',
        'period',
        'is_default',
        'status',
    ];
}
