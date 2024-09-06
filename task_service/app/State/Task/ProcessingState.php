<?php

namespace App\State\Task;

use App\Enums\TaskStatusEnum;
use App\Models\Task;

class ProcessingState extends BaseState
{
    public function handle(Task $task): void
    {
        $task->update([
            'status' => TaskStatusEnum::PROCESSING->value,
            'started_at' => now()->format('Y-m-d H:i:s'),
            'finished_at' => null,
        ]);
    }

    public function toNext(Task $task): void
    {
        $this->handleStateByEqualStatus(
            TaskStatusEnum::PROCESSING->value,
            new FinishedState(),
            $task
        );
    }

    public function toBack(Task $task): void
    {
        $this->handleStateByEqualStatus(
            TaskStatusEnum::PROCESSING->value,
            new CreatedState(),
            $task
        );
    }
}
