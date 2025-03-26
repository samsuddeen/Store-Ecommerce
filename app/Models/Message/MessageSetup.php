<?php

namespace App\Models\Message;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MessageSetup extends Model
{
    use HasFactory;
    protected $fillable=[
        'created_by',
        'title',
        'message',
    ];
    public function owner():BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by')->withDefault();
    }
}
