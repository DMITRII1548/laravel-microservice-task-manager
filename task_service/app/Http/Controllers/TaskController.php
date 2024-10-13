<?php

namespace App\Http\Controllers;

use App\Http\Requests\Task\StoreRequest;
use App\Http\Requests\Task\UpdateRequest;
use App\Http\Resources\Task\TaskResource;
use App\Models\User;
use App\Services\TaskService;
use Illuminate\Http\Response;

class TaskController extends Controller
{
    public function __construct(
        private readonly TaskService $taskService
    ) {}

    public function index(int $user): array
    {
        $tasks = $this->taskService->getTasksByUserId($user);

        return TaskResource::collection($tasks)->resolve();
    }

    public function store(StoreRequest $request, User $user): array
    {
        $data = $request->validated();

        $task = $this->taskService->store($user, $data);

        return TaskResource::make($task)->resolve();
    }

    public function show(int $user, int $task): array
    {
        $task = $this->taskService->getTask($user, $task);

        return TaskResource::make($task)->resolve();
    }

    public function update(UpdateRequest $request, int $user, int $task): Response
    {
        $data = $request->validated();

        return $this->taskService->update($user, $task, $data)
            ? response([
                'updated' => true,
            ])
            : response([
                'updated' => false,
            ]);
    }

    public function destroy(int $user, int $task): Response
    {
        return $this->taskService->destroy($user, $task)
            ? response([
                'deleted' => true,
            ])
            : response([
                'deleted' => false,
            ]);
    }

    public function toNextStatus(int $user, int $task): Response
    {
        $newStatus = $this->taskService->toNextStatus($user, $task);

        return response([
            'updated' => true,
            'new_status' => $newStatus,
        ]);
    }

    public function toBackStatus(int $user, int $task): Response
    {
        $newStatus = $this->taskService->toBackStatus($user, $task);

        return response([
            'updated' => true,
            'new_status' => $newStatus,
        ]);
    }
}
