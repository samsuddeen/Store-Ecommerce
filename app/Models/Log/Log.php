<?php

namespace App\Models\Log;

use App\Models\New_Customer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Seller;
use App\Models\User;
class Log extends Model
{
    use HasFactory;
    protected $fillable=[
        'log_model',
        'log_role',
        'guard',
        'log_id',
        'log_title',
        'url',
        'action',
    ];

    public function user()
    {
        return $this->hasOne(New_Customer::class,'id','log_id');
    }
    public function seller()
    {
        return $this->hasOne(Seller::class,'id','log_id');
    }
    public function admin()
    {
        return $this->hasOne(User::class,'id','log_id');
    }
}
