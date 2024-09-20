<?php

namespace App\Listeners;

use App\Events\UserCreated;
use App\Factories\AMQPConnectionFactory;
use App\Http\Resources\User\UserMinifiedResource;
use PhpAmqpLib\Message\AMQPMessage;

class PublishUser
{
    /**
     * Handle the event.
     */
    public function handle(UserCreated $event): void
    {
        $userData = json_encode(UserMinifiedResource::make($event->user)->resolve());

        $connection = AMQPConnectionFactory::make();
        $channel = $connection->channel();

        $channel->queue_declare('tasks', false, true, false, false);
        $channel->queue_declare('profiles', false, true, false, false);

        $msgTasks = new AMQPMessage($userData);
        $channel->basic_publish($msgTasks, '', 'tasks');

        $msgProfiles = new AMQPMessage($userData);
        $channel->basic_publish($msgProfiles, '', 'profiles');

        $channel->close();
        $connection->close();
    }
}
