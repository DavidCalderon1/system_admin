<?php

namespace App\Providers;

use App\Repositories\EloquentUserRepository;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * @var string[]
     */
    protected $classes = [
        UserRepositoryInterface::class => EloquentUserRepository::class
    ];

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        foreach ($this->classes as $abstract => $class) {
            $this->app->bind($abstract, function () use ($class) {
                return app($class);
            });
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
