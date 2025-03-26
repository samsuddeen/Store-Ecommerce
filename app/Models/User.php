<?php

namespace App\Models;

use App\Models\Notification\TaskNotification;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Task\Task;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use HasRoles;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'additional_address',
        'province',
        'district',
        'country',
        'company',
        'gender',
        'photo',
        'agree',
        'area',
        'member_id',
        'provider_id',
        'provider',
        'avatar',
        'remember_token',
        'address'
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
        'status' => 'boolean',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return  \Illuminate\Database\Eloquent\Relations\HasMany;
     */

    public function activities(): HasMany
    {
        return $this->hasMany(LogActivity::class, 'user_id')->latest('log_activities.id')->limit(10);
    }


    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'user_id');
    }

    public function product(): HasMany
    {
        return $this->hasMany(Product::class, 'user_id');
    }


  

    public function userBillingAddress()
    {
        return $this->hasOne(UserBillingAddress::class,'user_id','id');
    }


    public function customer()
    {
        return $this->hasOne(New_Customer::class);
    }

    public function product_return()
    {
        return $this->hasOne(ReturnProduct::class);
    }
    public function isSuperAdmin()
    {
        $role = auth()->user()->getRoleNames()[0];
        return  Str::slug($role) == Str::slug("super admin") ? true : false;
    }
    public function isAdmin()
    {
        $role = auth()->user()->getRoleNames()[0];
        return  Str::slug($role) == Str::slug("admin") ? true : false;
    }
    public function getRoleName()
    {
        $role = auth()->user()->getRoleNames()[0];
        return $role;
    }

    public function task()
    {
        return $this->belongsToMany(Task::class, 'task_assigns','assigned_to','task_id');
    }

    public function subTask()
    {
        return $this->belongsTo(SubTask::class, 'subtask_id');
    }

    public function notifications()
    {
        return $this->morphMany(TaskNotification::class, 'notifiable')->orderBy('created_at', 'desc');
    }
}
