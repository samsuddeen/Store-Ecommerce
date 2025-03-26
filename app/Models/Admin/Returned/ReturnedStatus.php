<?php

namespace App\Models\Admin\Returned;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnedStatus extends Model
{
    use HasFactory;
    protected $fillable=[
        'updated_by',
        'return_id',
        'status',
        'date',
        'remarks',
    ];
}
