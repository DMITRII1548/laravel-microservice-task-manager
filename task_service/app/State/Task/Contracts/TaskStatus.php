<?php

namespace App\State\Task\Contracts;

use App\Models\Task;

interface TaskStatus
{
    public function handle(Task $task): void;

    public function toNext(Task $task): void;

    public function toBack(Task $task): void;
}
