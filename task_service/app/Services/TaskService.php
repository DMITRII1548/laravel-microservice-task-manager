<?php

namespace App\Services;

use App\Models\Task;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TaskService
{
    public function getTasksByUserId(int $id): LengthAwarePaginator
    {
        $tasks = Task::query()
            ->where('user_id', $id)
            ->paginate(30);

        return $tasks;
    }

    public function getTask(int $userId, int $taskId): Task
    {
        $task = Task::query()
            ->where('user_id', $userId)
            ->where('id', $taskId)
            ->firstOrFail();

        return $task;
    }

    public function destroy(int $userId, int $taskId): ?bool
    {
        $task = $this->getTask($userId, $taskId);

        return $task->delete();
    }

    public function store(User $user, array $data): Task
    {
        $task = $user
            ->tasks()
            ->create($data);

        // Refresh task, because getting null status, but status has to be CREATED
        $task = $task->refresh();

        return $task;
    }

    public function update(int $userId, int $taskId, array $data): bool
    {
        $task = $this->getTask($userId, $taskId);

        $isUpdated = $task->update($data);

        return $isUpdated;
    }
}
