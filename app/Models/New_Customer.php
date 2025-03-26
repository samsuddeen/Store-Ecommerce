<?php

namespace App\Models;

use App\Enum\Gender;
use App\Models\Admin\Coupon\Customer\CustomerCoupon;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\SoftDeletes;

class New_Customer extends Model implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract                                                                                                                    
{
    use Authenticatable, Authorizable, CanResetPassword, MustVerifyEmail;
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use HasRoles;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
    */
    protected $table="tbl_customers";
    protected $guard = 'customer';
    // public $primaryKey=['email'];
    // protected $primaryKey='email'; 
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'status',
        'zip',
        'verify_token',
        'verify_otp',
        'province',
        'district',
        'area',
        'photo',
        'email_verified_at',
        'social_provider',
        'provider_id',
        'data',
        'fb_id',
        'google_id',
        'socialite',
        'user_id',
        'social_avatar',
        'referal_code',
        'fb_status',
        'wholeseller',
        'bussiness_name',
        'country_id',
        'pan_num'
        // 'agree',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        // 'status' => 'boolean',
        'gender' => Gender::class,
        'birthday' => 'date',
        "wholeseller" => "boolean"
    ];

   

    /**
     * Get all of the wishlists for the Customer
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class, 'customer_id');
    }



    public function userBillingAddress()
    {
        return $this->hasOne(UserBillingAddress::class,'user_id','id');
    }

    public function carts(){
        return $this->hasMany(Cart::class,'user_id');
    }

    public function UserShippingAddress()
    {
        return $this->hasOne(UserShippingAddress::class,'user_id','id');
    }
    public function ccustomerCoupon()
    {
        return $this->hasMany(CustomerCoupon::class, 'customer_pid');
    }

    public function getCart(){
        return $this->hasOne(Cart::class,'user_id','id');
    }

   

}
