<?php

namespace App\State\Task;

use App\Enums\TaskStatusEnum;
use App\Models\Task;

class CreatedState extends BaseState
{
    public function handle(Task $task): void
    {
        $task->update([
            'status' => TaskStatusEnum::CREATED->value,
            'started_at' => null,
            'finished_at' => null,
        ]);
    }

    public function toNext(Task $task): void
    {
        $this->handleStateByEqualStatus(
            TaskStatusEnum::CREATED->value,
            new ProcessingState,
            $task
        );
    }

    public function toBack(Task $task): void {}
}
