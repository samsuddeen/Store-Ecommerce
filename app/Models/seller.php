<?php

namespace App\Models;

use App\Models\Seller\SellerDocument;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Seller extends Authenticatable
{   

    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use HasRoles;
    use SoftDeletes;

    protected $table='sellers';
    protected $guard_name = "seller";   
    protected $fillable=[
        'created_by',
        'user_id',
        'email',
        'name',
        'phone',
        'password',
        'address',
        'status',
        'photo',
        'province_id',
        'district_id',
        'area',
        'verify_otp',
        'verify_token',
        'zip',
        'company_name',
        'is_new',
        'email_verified_at',
        'slug'
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id')->withDefault();
    }
    public function owner():BelongsTo   
    {
        return $this->belongsTo(User::class, 'created_by')->withDefault();
    }
    public function documents():HasMany
    {
        return $this->hasMany(SellerDocument::class, 'seller_id');
    }

    

    public function getSlugs($title)
    {
        $slug=\Str::slug($title);
        if($this->where('slug',$slug)->count() >0)
        {
            $slug=$slug."-".rand(0,9999);
            $this->getSlugs($slug);
        }
        return $slug;
    }

    public function province()
    {
        dd('plljk');
        return $this->hasOne(Province::class,'id','province_id');
    }

    public function district()
    {
        return $this->hasOne(District::class,'id','district_id');
    }

  

}

