<?php

namespace App\Factories;

use App\Exceptions\MicroserviceNotFoundException;
use App\Factories\Contracts\HttpClient;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class HttpClientFactory implements HttpClient
{
    public static function make(string $service, float $timeout = 5.0): Client
    {
        $microservices = config('microservices');

        if (isset($microservices[$service])) {
            return new Client([
                'base_uri' => $microservices[$service]['url'],
                'timeout' => $timeout,
            ]);
        } else {
            $message = "Microservice $service not found";
            Log::info($message);

            throw new MicroserviceNotFoundException($message);
        }
    }
}
