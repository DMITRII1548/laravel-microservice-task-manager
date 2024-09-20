<?php

namespace App\Factories\Contracts;

use PhpAmqpLib\Connection\AMQPStreamConnection;

interface AMQPConnection
{
    public static function make(): AMQPStreamConnection;
}
