<?php

namespace App\Services;

use App\Notifications\ProjectFlowNotification;
use Illuminate\Support\Collection;

class ProjectNotifier
{
    public static function notifyUsers(iterable $users, array $payload): void
    {
        $sent = [];

        foreach ($users as $user) {
            if (!$user) continue;
            if (in_array($user->id, $sent)) continue;

            $user->notify(new ProjectFlowNotification($payload));
            $sent[] = $user->id;
        }
    }

    public static function makePayload($project, array $data): array
    {
        return array_merge([
            'project_id'   => $project->id,
            'project_name' => $project->project_name,
            'project_type' => $project->project_type,
            'created_at'   => now(),
        ], $data);
    }
}