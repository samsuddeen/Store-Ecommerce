<?php

namespace App\Observers;

use App\Models\Task\Task;
use App\Models\Task\TaskLog;

class TaskObserver
{   
    public function reassignTask(Task $task, $reason)
    {
        TaskLog::create([
            'task_id' => $task->id,
            'reason' => $reason,
        ]);
    }
}
