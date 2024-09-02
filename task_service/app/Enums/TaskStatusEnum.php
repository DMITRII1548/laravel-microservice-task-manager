<?php

namespace App\Enums;

enum TaskStatusEnum: string
{
    case CREATED = 'CREATED';
    case PROCESSING = 'PROCESSING';
    case FINISHED = 'FINISHED';
    case CANCELED = 'CANCELED';
}
