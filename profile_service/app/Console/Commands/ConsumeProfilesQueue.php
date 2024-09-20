<?php

namespace App\Console\Commands;

use App\Factories\AMQPConnectionFactory;
use App\Models\User;
use Illuminate\Console\Command;

class ConsumeProfilesQueue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rabbit:consume-profiles-queue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command are consuming profiles queue';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $connection = AMQPConnectionFactory::make();
        $channel = $connection->channel();

        $channel->queue_declare('profiles', false, true, false, false);

        $callback = function ($msg) {
            $userData = json_decode($msg->body, true);
            User::create($userData);
        };

        $channel->basic_consume('profiles', '', false, true, false, false, $callback);

        while ($channel->is_consuming()) {
            $channel->wait();
        }

        $channel->close();
        $connection->close();
    }
}
