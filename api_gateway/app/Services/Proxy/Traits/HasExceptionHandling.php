<?php

namespace App\Services\Proxy\Traits;

use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

trait HasExceptionHandling
{
    protected function handleServerErrorException(
        string $logMessage,
        string $responseMessage = 'Server error'
    ): Response {
        Log::error($logMessage);

        return response([
            'message' => $responseMessage,
        ], 500);
    }

    protected function handleConnectErrorException(
        string $logMessage,
        string $responseMessage = 'Failed connecting to remote server'
    ): Response {
        Log::error($logMessage);

        return response([
            'message' => $responseMessage,
        ], 500);
    }

    protected function handleClientErrorException(
        ClientException $e,
        array $response = []
    ): Response|JsonResponse {
        if ($e->getCode() === 422) {
            return response()->json(
                [
                    'error' => json_decode((string) $e->getResponse()->getBody()),
                ],
                $e->getCode()
            );
        }

        Log::error($e->getMessage());

        return response($response, $e->getCode());
    }
}
