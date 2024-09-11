<?php

namespace App\Services\Proxy\Traits;

use Exception;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;

trait HasHttp
{
    use HasExceptionHandling;

    public function sendHttp(callable $handler): mixed
    {
        try {
            return $handler();
        } catch (ClientException $e) {
            return $this->handleClientErrorException($e);
        } catch (ConnectException $e) {
            return $this->handleConnectErrorException($e->getMessage());
        } catch (Exception $e) {
            return $this->handleServerErrorException($e->getMessage());
        }
    }
}
