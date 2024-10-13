<?php

namespace App\State\Task;

use App\Models\Task;
use App\State\Task\Contracts\TaskStatus;

abstract class BaseState implements TaskStatus
{
    protected function handleStateByEqualStatus(
        string $status,
        TaskStatus $state,
        Task $task
    ): void {
        if ($task->status === $status) {
            $state->handle($task);
        }
    }
}
