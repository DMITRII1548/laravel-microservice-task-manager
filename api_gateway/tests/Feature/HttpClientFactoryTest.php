<?php

namespace Tests\Feature;

use App\Exceptions\MicroserviceNotFoundException;
use App\Factories\HttpClientFactory;
use GuzzleHttp\Client;
use Tests\TestCase;

class HttpClientFactoryTest extends TestCase
{
    public function test_make_http_guzzle_client_successfully(): void
    {
        $client = HttpClientFactory::make('profile_service');

        $this->assertInstanceOf(Client::class, $client);
    }

    public function test_make_client_throws_exception_for_non_existing_service(): void
    {
        $this->expectException(MicroserviceNotFoundException::class);
        $this->expectExceptionMessage('Microservice non_existent_service not found');

        HttpClientFactory::make('non_existent_service');
    }
}
