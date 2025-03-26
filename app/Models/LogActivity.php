<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;

class LogActivity extends Model
{
    use HasFactory;
    protected $fillable = [
        'action',
        'url',
        'method',
        'ip',
        'agent',
    ];

    public static function addActivity($action, $userId = null)
    {
        Self::create(
            [
                'action' => json_encode($action),
                'url' => Request::fullUrl(),
                'method' => Request::method(),
                'ip' => Request::ip(),
                'agent' => Request::header('user-agent'),
                'user_id' => $userId ?? auth()->id() ?? null,
            ]
        );
    }
}
