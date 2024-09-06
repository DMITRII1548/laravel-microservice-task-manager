<?php

namespace App\State\Task;

use App\Enums\TaskStatusEnum;
use App\Models\Task;

class CanceledState extends BaseState
{
    public function handle(Task $task): void
    {
        $task->update([
            'status' => TaskStatusEnum::CANCELED->value,
        ]);
    }

    public function toNext(Task $task): void {}

    public function toBack(Task $task): void
    {
        $this->handleStateByEqualStatus(
            TaskStatusEnum::CANCELED->value,
            new CreatedState,
            $task
        );
    }
}
