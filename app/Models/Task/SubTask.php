<?php

namespace App\Models\Task;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Order;
use App\Models\User;
use App\Models\Task\Action;
use App\Models\Product;
use App\Models\Task\TaskAssign;

class SubTask extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "sub_tasks";

    protected $fillable = [
        'task_id',
        'title',
        'description',
        'status',
        'priority',
        'action_id',
        'start_date',
        'due_date',
        'assigned_by',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function task()
    {
        return $this->belongsTo(Task::class, 'task_id');
    }

    public function action()
    {
        return $this->belongsTo(Action::class, 'action_id');
    }

    public function assigns()
    {
        return $this->belongsToMany(User::class, 'sub_task_assigns', 'subtask_id', 'assigned_to');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class,'created_by');
    }

}
