<?php

namespace App\Listeners;

use App\Services\ProjectNotifier;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendTaskNotification
{
    public function handle($event)
    {
        $task = $event->task;
        $project = $task->project;

        $map = [
            \App\Events\TaskAssigned::class      => 'task_assigned',
            \App\Events\TaskFileUploaded::class  => 'task_file_uploaded',
            \App\Events\TaskApproved::class      => 'task_approved',
            \App\Events\TaskRejected::class      => 'task_rejected',
        ];

        $eventKey = $map[get_class($event)] ?? null;
        if (!$eventKey) return;

        $cfg = config("project_events.$eventKey");
        if (!$cfg) return;

        $targets = collect();

        // 🎯 PIC
        if ($task->employee?->user) {
            $targets->put('assigned', $task->employee->user);
        }

        // 🛠️ Admin / creator project
        if ($project->createdBy) {
            $targets->put('admin', $project->createdBy);
        }

        $targets->each(function ($user, $role) use ($cfg, $project, $task, $eventKey) {
            if (!isset($cfg['message'][$role])) return;

            ProjectNotifier::notifyUsers(
                [$user],
                ProjectNotifier::makePayload($project, [
                    'type'    => $eventKey,
                    'role'    => $role,
                    'title'   => $cfg['title'],
                    'message' => $cfg['message'][$role],
                    'url'     => route('projects.create', ['project_id' => $project->id]),
                    'meta'    => [
                        'task_id' => $task->id,
                    ],
                ])
            );
        });
    }
}

