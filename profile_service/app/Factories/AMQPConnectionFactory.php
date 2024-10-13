<?php

namespace App\Factories;

use App\Factories\Contracts\AMQPConnection;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class AMQPConnectionFactory implements AMQPConnection
{
    public static function make(): AMQPStreamConnection
    {
        return new AMQPStreamConnection(
            config('rabbitmq.host'),
            config('rabbitmq.port'),
            config('rabbitmq.user'),
            config('rabbitmq.password'),
            config('rabbitmq.vhost'),
        );
    }
}
