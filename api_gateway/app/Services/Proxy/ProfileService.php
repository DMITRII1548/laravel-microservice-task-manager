<?php

namespace App\Services\Proxy;

use App\Factories\HttpClientFactory;
use App\Models\User;
use App\Services\Proxy\Traits\HasHttp;
use GuzzleHttp\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProfileService
{
    use HasHttp;

    private Client $client;

    private User $user;

    public function __construct()
    {
        $this->user = auth()->user();

        $this->client = HttpClientFactory::make('profile_service');
    }


    public function store(Request $request): Response|JsonResponse
    {
        $multipart = $this->convertRequestToMultipart($request);

        return $this->sendHttp(function () use ($multipart): Response|JsonResponse {
            $response = $this->client->post("/api/users/{$this->user->id}/profile/", [
                'multipart' => $multipart,
                'headers' => [
                    'Accept' => 'application/json',
                ],
            ]);

            return response()->json(
                json_decode($response->getBody()->getContents()),
                $response->getStatusCode()
            );
        });
    }

    public function getProfile(): Response|JsonResponse
    {
        return $this->sendHttp(function (): Response|JsonResponse {
            $response = $this->client->get("/api/users/{$this->user->id}/profile/");

            return response()->json(
                json_decode($response->getBody()->getContents()),
                $response->getStatusCode()
            );
        });
    }

    public function update(Request $request): Response|JsonResponse
    {
        $multipart = $this->convertRequestToMultipart($request);
        $multipart[] = [
            'name' => '_method',
            'contents' => 'PATCH'
        ];

        return $this->sendHttp(function () use ($multipart): Response|JsonResponse {
            $response = $this->client->post("/api/users/{$this->user->id}/profile/", [
                'multipart' => $multipart,
                'headers' => [
                    'Accept' => 'application/json',
                ],
            ]);

            return response()->json(
                json_decode($response->getBody()->getContents()),
                $response->getStatusCode()
            );
        });
    }

    public function destroy(): Response|JsonResponse
    {
        return $this->sendHttp(function (): Response|JsonResponse {
            $response = $this->client->delete("/api/users/{$this->user->id}/profile/");

            return response()->json(
                json_decode($response->getBody()->getContents()),
                $response->getStatusCode()
            );
        });
    }

    private function convertRequestToMultipart(Request $request): array
    {
        $multipart = [];

        if ($request->hasFile('image')) {
            $multipart[] = [
                'name' => 'image',
                'contents' => fopen($request->file('image')->getPathname(), 'r'),
                'filename' => $request->file('image')->getClientOriginalName(),
            ];
        }

        $fields = $request->all();
        if (isset($fields['image'])) unset($fields['image']);
        if (isset($fields['_method'])) unset($fields['_method']);

        foreach ($fields as $key => $value) {
            $multipart[] = [
                'name' => $key,
                'contents' => $value,
            ];
        }

        return $multipart;
    }
}

