<?php

namespace App\Models\Trash;

use App\Models\Seller;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Trash extends Model
{
    use HasFactory;
    protected $fillable=[
        'user_id',
        'guard',
        'model',
        'name',
        'model_id',
    ];
    public function owner(): BelongsTo
    {
        if($this->guard == 'seller'){
            return $this->belongsTo(Seller::class, 'user_id')->withDefault();
        }
        return $this->belongsTo(User::class, 'user_id')->withDefault();
    }
}
