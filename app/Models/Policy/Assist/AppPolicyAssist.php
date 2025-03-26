<?php

namespace App\Models\Policy\Assist;

use App\Models\Policy\AppPolicy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AppPolicyAssist extends Model
{
    use HasFactory;
    protected $fillable=[
        'policy_id',
        'title',
        'policy',
    ];
    public function year():BelongsTo
    {
        return $this->belongsTo(AppPolicy::class, 'policy_id')->withDefault();
    }
}
