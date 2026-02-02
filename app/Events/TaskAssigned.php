<?php

namespace App\Events;

use App\Models\ProjectTask;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TaskAssigned
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public ProjectTask $task)
    {
        $this->task->load('project', 'employee.user');
    }
}
