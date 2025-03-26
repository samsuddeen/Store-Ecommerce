<?php

namespace App\Models\Task;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubTaskAssign extends Model
{
    use HasFactory;

    protected $table = "sub_task_assigns";

    protected $fillable = [
        'subtask_id',
        'assigned_to',
    ];

    public function subTask()
    {
        return $this->belongsTo(SubTask::class, 'subtask_id');
    }

    
    public function assignedTo()
    {
        return $this->belongsToMany(SubTaskAssign::class, 'sub_task_assigns', 'subtask_id', 'assigned_to');
    }
}
