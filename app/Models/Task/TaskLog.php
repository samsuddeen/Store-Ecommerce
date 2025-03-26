<?php

namespace App\Models\Task;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskLog extends Model
{
    use HasFactory;

    protected $table = "task_logs";

    protected $fillable = [
        'task_id',
        'reassigned_by',
        'assigned_to',
        'reason'
    ];

    public function task()
    {
        return $this->belongsTo(Task::class, 'task_id');
    }

    public function reassignedBy()
    {
        return $this->belongsTo(User::class, 'reassigned_by');
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
