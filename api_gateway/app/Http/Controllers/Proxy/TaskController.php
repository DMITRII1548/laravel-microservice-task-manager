<?php

namespace App\Http\Controllers\Proxy;

use App\Factories\HttpClientFactory;
use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

// Обработать исключение при долгом ожидании \GuzzleHttp\Exception\ConnectException
class TaskController extends Controller
{
    private Client $client;
    private User $user;

    public function __construct()
    {
        $this->user = auth()->user();

        $this->client = HttpClientFactory::make('task_service');
    }

    public function index(): Response|JsonResponse
    {
        try {
            $response = $this->client->get("/api/users/{$this->user->id}/tasks");

            return response()->json(
                json_decode($response->getBody()->getContents()),
                $response->getStatusCode()
            );
        } catch (ConnectException $e) {
            Log::error($e->getMessage());

            return response([
                'message' => 'Failed connecting to remote server',
            ], 500);
        } catch (Exception $e) {
            Log::error($e->getMessage());

            return response([
                'message' => 'Server error',
            ], 500);
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
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            if ($e->getCode() === 422) {
                return response()->json(
                    [
                        'error' => json_decode((string) $e->getResponse()->getBody()),
                    ],
                    $e->getCode()
                );
            }

            Log::error($e->getMessage());

            return response([], $e->getCode());
        } catch (ConnectException $e) {
            Log::error($e->getMessage());

            return response([
                'message' => 'Failed connecting to remote server',
            ], 500);
        }  catch (Exception $e) {
            Log::error($e->getMessage());

            return response([
                'message' => 'Server error',
            ], 500);
        }
    }

    public function show(int $task): Response|JsonResponse
    {
        try {
            $response = $this->client->get("/api/users/{$this->user->id}/tasks/{$task}");

            return response()->json(
                json_decode($response->getBody()->getContents()),
                $response->getStatusCode()
            );
        } catch (ConnectException $e) {
            Log::error($e->getMessage());

            return response([
                'message' => 'Failed connecting to remote server',
            ], 500);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            return response([], $e->getCode());
        } catch (Exception $e) {
            Log::error($e->getMessage());

            return response([
                'message' => 'Server error',
            ], 500);
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
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            if ($e->getCode() === 422) {
                return response()->json(
                    [
                        'error' => json_decode((string) $e->getResponse()->getBody()),
                    ],
                    $e->getCode()
                );
            }

            Log::error($e->getMessage());

            return response([], $e->getCode());
        } catch (ConnectException $e) {
            Log::error($e->getMessage());

            return response([
                'message' => 'Failed connecting to remote server',
            ], 500);
        } catch (Exception $e) {
            Log::error($e->getMessage());

            return response([
                'message' => 'Server error',
            ], 500);
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
            Log::error($e->getMessage());

            return response([
                'message' => 'Failed connecting to remote server',
            ], 500);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            return response([], $e->getCode());
        } catch (Exception $e) {
            Log::error($e->getMessage());

            return response([
                'message' => 'Server error',
            ], 500);
        }
    }
}
