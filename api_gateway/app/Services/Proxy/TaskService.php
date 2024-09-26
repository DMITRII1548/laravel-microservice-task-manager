<?php

namespace App\Services\Proxy;

use App\Factories\HttpClientFactory;
use App\Models\User;
use App\Services\Proxy\Traits\HasHttp;
use GuzzleHttp\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TaskService
{
    use HasHttp;

    private Client $client;

    private User $user;

    public function __construct()
    {
        $this->user = auth()->user();

        $this->client = HttpClientFactory::make('task_service');
    }

    public function getTasks(): Response|JsonResponse
    {
        $page = request()->get('page') ?? 1;

        return $this->sendHttp(function () use ($page): Response|JsonResponse {
            $response = $this->client->get("/api/users/{$this->user->id}/tasks?page=$page");

            return response()->json(
                json_decode($response->getBody()->getContents()),
                $response->getStatusCode()
            );
        });
    }

    public function store(Request $request): Response|JsonResponse
    {
        return $this->sendHttp(function () use ($request): Response|JsonResponse {
            $response = $this->client->post("/api/users/{$this->user->id}/tasks", [
                'json' => $request->all(),
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ],
            ]);

            return response()->json(
                json_decode($response->getBody()->getContents()),
                $response->getStatusCode()
            );
        });
    }

    public function getTask(int $task): Response|JsonResponse
    {
        return $this->sendHttp(function () use ($task): Response|JsonResponse {
            $response = $this->client->get("/api/users/{$this->user->id}/tasks/{$task}");

            return response()->json(
                json_decode($response->getBody()->getContents()),
                $response->getStatusCode()
            );
        });
    }

    public function update(Request $request, int $task): Response|JsonResponse
    {
        return $this->sendHttp(function () use ($task, $request): Response|JsonResponse {
            $response = $this->client->patch("/api/users/{$this->user->id}/tasks/{$task}", [
                'json' => $request->all(),
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ],
            ]);

            return response()->json(
                json_decode($response->getBody()->getContents()),
                $response->getStatusCode()
            );
        });
    }

    public function destroy(int $task): Response|JsonResponse
    {
        return $this->sendHttp(function () use ($task): Response|JsonResponse {
            $response = $this->client->delete("/api/users/{$this->user->id}/tasks/{$task}");

            return response()->json(
                json_decode($response->getBody()->getContents()),
                $response->getStatusCode()
            );
        });
    }

    public function updateToNextStatus(int $task): Response|JsonResponse
    {
        return $this->sendHttp(function () use ($task): Response|JsonResponse {
            $response = $this->client->patch("/api/users/{$this->user->id}/tasks/{$task}/status/next");

            return response()->json(
                json_decode($response->getBody()->getContents()),
                $response->getStatusCode()
            );
        });
    }

    public function updateToBackStatus(int $task): Response|JsonResponse
    {
        return $this->sendHttp(function () use ($task): Response|JsonResponse {
            $response = $this->client->patch("/api/users/{$this->user->id}/tasks/{$task}/status/back");

            return response()->json(
                json_decode($response->getBody()->getContents()),
                $response->getStatusCode()
            );
        });
    }
}
