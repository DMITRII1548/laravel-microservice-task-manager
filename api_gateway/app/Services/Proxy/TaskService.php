<?php

namespace App\Services\Proxy;

use App\Factories\HttpClientFactory;
use App\Models\User;
use App\Services\Proxy\Traits\HasExceptionHandling;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TaskService
{
    use HasExceptionHandling;

    private Client $client;
    private User $user;

    public function __construct()
    {
        $this->user = auth()->user();

        $this->client = HttpClientFactory::make('task_service');
    }

    public function getTasks(): Response|JsonResponse
    {
        try {
            $response = $this->client->get("/api/users/{$this->user->id}/tasks");

            return response()->json(
                json_decode($response->getBody()->getContents()),
                $response->getStatusCode()
            );
        } catch (ConnectException $e) {
            return $this->handleConnectErrorException($e->getMessage());
        } catch (Exception $e) {
            return $this->handleServerErrorException($e->getMessage());
        }
    }

    public function store(Request $request): Response|JsonResponse
    {
        try {
            $response = $this->client->post("/api/users/{$this->user->id}/tasks", [
                'json' => $request->all(),
                'headers' => [
                    'Accept'        => 'application/json',
                    'Content-Type'  => 'application/json',
                ],
            ]);

            return response()->json(
                json_decode($response->getBody()->getContents()),
                $response->getStatusCode()
            );
        } catch (ClientException $e) {
            return $this->handleClientErrorException($e);
        } catch (ConnectException $e) {
            return $this->handleConnectErrorException($e->getMessage());
        }  catch (Exception $e) {
            return $this->handleServerErrorException($e->getMessage());
        }
    }

    public function getTask(int $task): Response|JsonResponse
    {
        try {
            $response = $this->client->get("/api/users/{$this->user->id}/tasks/{$task}");

            return response()->json(
                json_decode($response->getBody()->getContents()),
                $response->getStatusCode()
            );
        } catch (ConnectException $e) {
            return $this->handleConnectErrorException($e->getMessage());
        } catch (ClientException $e) {
            return $this->handleClientErrorException($e);
        } catch (Exception $e) {
            return $this->handleServerErrorException($e->getMessage());
        }
    }

    public function update(Request $request, int $task): Response|JsonResponse
    {
        try {
            $response = $this->client->patch("/api/users/{$this->user->id}/tasks/{$task}", [
                'json' => $request->all(),
                'headers' => [
                    'Accept'        => 'application/json',
                    'Content-Type'  => 'application/json',
                ],
            ]);

            return response()->json(
                json_decode($response->getBody()->getContents()),
                $response->getStatusCode()
            );
        } catch (ClientException $e) {
            return $this->handleClientErrorException($e);
        } catch (ConnectException $e) {
            return $this->handleConnectErrorException($e->getMessage());
        } catch (Exception $e) {
            return $this->handleServerErrorException($e->getMessage());
        }
    }

    public function destroy(int $task): Response|JsonResponse
    {
        try {
            $response = $this->client->delete("/api/users/{$this->user->id}/tasks/{$task}");

            return response()->json(
                json_decode($response->getBody()->getContents()),
                $response->getStatusCode()
            );
        } catch (ConnectException $e) {
            return $this->handleConnectErrorException($e->getMessage());
        } catch (ClientException $e) {
            return $this->handleClientErrorException($e);
        } catch (Exception $e) {
            return $this->handleServerErrorException($e->getMessage());
        }
    }
}

