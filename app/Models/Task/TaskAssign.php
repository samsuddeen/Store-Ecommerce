<?php

namespace App\Models\Task;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Notifications\TaskAssignedNotification;

class TaskAssign extends Model
{
    use HasFactory;

    protected $table = "task_assigns";

    protected $fillable = [
        'task_id','assigned_to'
    ];

    public function task()
    {
        return $this->belongsTo(Task::class, 'task_id');
    }

    public function assignedTo()
    {
        return $this->belongsToMany(User::class, 'task_assigns', 'task_id', 'assigned_to');
    }

    public function sendNotification()
    {   
        $assignedUsers = $this->assignedTo()->get();
        if($assignedUsers)
        {
            $notification = new TaskAssignedNotification($this);
            // $assignedUsers->notify($notification);
            foreach($assignedUsers as $assignedUser)
            {   
                $assignedUser->notify($notification);   
            }
        }


    }
}
