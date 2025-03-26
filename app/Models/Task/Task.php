<?php

namespace App\Models\Task;

use App\Models\Order;
use App\Models\User;
use App\Models\Task\Action;
use App\Models\Product;
use App\Models\Task\TaskAssign;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "tasks";

    protected $fillable = [
        'title',
        'description',
        'status',
        'priority',
        'start_date',
        'due_date',
        'action_id',
        'order_id',
        'product_id',
        'assigned_by',
        'created_by',
        'updated_by',
        'deleted_by',
        'reassigned_by',
        'reassign_date_time'
    ];

    public function action()
    {
        return $this->belongsTo(Action::class, 'action_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    } 

    public function reassignedBy()
    {
        return $this->belongsTo(User::class,'reassigned_by');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'task_assigns', 'task_id', 'assigned_to');
    }

    public function assigns()
    {
        return $this->belongsToMany(User::class, 'task_assigns', 'task_id', 'assigned_to');
    }

    public function subTasks()
    {
        return $this->hasMany(SubTask::class);
    }
}