<?php

namespace App\Models\Policy;

use App\Models\Policy\Assist\AppPolicyAssist;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AppPolicy extends Model
{
    use HasFactory;
    protected $fillable=[
        'created_by',
        'year',
    ];
    public function assist():HasMany
    {
        return $this->hasMany(AppPolicyAssist::class, 'policy_id');
    }
}
