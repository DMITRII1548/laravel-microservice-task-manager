<?php

namespace App\State\Task;

use App\Enums\TaskStatusEnum;
use App\Models\Task;

class FinishedState extends BaseState
{
    public function handle(Task $task): void
    {
        $task->update([
            'status' => TaskStatusEnum::FINISHED->value,
            'finished_at' => now()->format('Y-m-d H:i:s'),
        ]);
    }

    public function toNext(Task $task): void {}

    public function toBack(Task $task): void
    {
        $this->handleStateByEqualStatus(
            TaskStatusEnum::FINISHED->value,
            new ProcessingState,
            $task
        );
    }
}
