<?php

namespace App\Providers;

use App\Interfaces\FileInterface;
use App\Interfaces\TaskInterface;
use App\Interfaces\UserInterface;
use App\Repositories\FileRepository;
use App\Repositories\TaskRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        /** INTERFACE AND REPOSITORY */
        $this->app->bind(UserInterface::class, UserRepository::class);
        $this->app->bind(FileInterface::class, FileRepository::class);
        $this->app->bind(TaskInterface::class, TaskRepository::class);
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
