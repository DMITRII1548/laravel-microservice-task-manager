<?php

return [
    'host' => env('RABBIT_MQ_HOST', 'localhost'),
    'port' => env('RABBIT_MQ_PORT', 15672),
    'user' => env('RABBIT_MQ_USER', 'guest'),
    'password' => env('RABBIT_MQ_PASSWORD', 'guest'),
    'vhost' => env('RABBIT_MQ_VHOST', '/'),
];
