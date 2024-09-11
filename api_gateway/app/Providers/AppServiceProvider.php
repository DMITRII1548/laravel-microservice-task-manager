<?php

namespace App\Providers;

use App\Services\Proxy\TaskService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(TaskService::class, fn (): TaskService => new TaskService());
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

    }
}
