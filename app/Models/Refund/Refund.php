<?php

namespace App\Models\Refund;

use App\Models\Admin\Returned\ReturnedStatus;
use App\Models\Customer\ReturnOrder;
use App\Models\New_Customer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Refund extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable=[
        'created_by',
        'return_id',
        'user_id',
        'is_new',
        'status',
        'paid_by'
    ];
    public function owner():BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by')->withDefault();
    }
    public function user():BelongsTo
    {
        return $this->belongsTo(New_Customer::class, 'user_id')->withDefault();
    }
    public function returnOrder():BelongsTo
    {
        return $this->belongsTo(ReturnOrder::class, 'return_id')->withDefault();
    }

    public function orderStatus()
    {
        return $this->hasOne(RefundStatus::class,'refund_id','id');
    }
}
