<?php

namespace App\Http\Controllers\Proxy;

use App\Http\Controllers\Controller;
use App\Services\Proxy\TaskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TaskController extends Controller
{
    public function __construct(
        private TaskService $taskService
    ) { }

    public function index(): Response|JsonResponse
    {
        return $this->taskService->getTasks();
    }

    public function store(Request $request): Response|JsonResponse
    {
        return $this->taskService->store($request);
    }

    public function show(int $task): Response|JsonResponse
    {
        return $this->taskService->getTask($task);
    }

    public function update(Request $request, int $task): Response|JsonResponse
    {
        return $this->taskService->update($request, $task);
    }

    public function destroy(int $task): Response|JsonResponse
    {
        return $this->taskService->destroy($task);
    }

    public function updateToNextStatus(int $task): Response|JsonResponse
    {
        return $this->taskService->updateToNextStatus($task);
    }

    public function updateToBackStatus(int $task): Response|JsonResponse
    {
        return $this->taskService->updateToBackStatus($task);
    }
}
