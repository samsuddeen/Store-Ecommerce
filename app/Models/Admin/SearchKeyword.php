<?php

namespace App\Models\Admin;

use App\Models\New_Customer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SearchKeyword extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'customer_id',
        'search_keyword',
        'ip_address',
        'mac_address',
        'browser',
        'system',
        'full_address',
    ];

    public function user()
    {
        return $this->hasOne(New_Customer::class,'id','customer_id');
    }
}
