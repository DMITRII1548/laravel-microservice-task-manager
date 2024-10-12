<?php

namespace App\Factories\Contracts;

use GuzzleHttp\Client;

interface HttpClient
{
    public static function make(string $service, float $timeout = 2.0): Client;
}
