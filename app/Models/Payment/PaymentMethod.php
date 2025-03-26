<?php

namespace App\Models\Payment;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentMethod extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable=[
        'user_id',
        'type',
        'title',
        'slug',
        '_token',
        'is_default',
        'status',
    ];
    protected $hidden=[
        '_token',
    ];
    public function getToken()
    {
        if($this->_token !== null){
            return Crypt::decryptString($this->_token);
        }
    }
    
}
