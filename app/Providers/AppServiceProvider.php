<?php

namespace App\Providers;

use App\Repositories\EloquentCityRepository;
use App\Repositories\EloquentCountryRepository;
use App\Repositories\EloquentPermissionRepository;
use App\Repositories\EloquentRoleRepository;
use App\Repositories\EloquentStateRepository;
use App\Repositories\EloquentUserRepository;
use App\Repositories\Interfaces\CityRepositoryInterface;
use App\Repositories\Interfaces\CountryRepositoryInterface;
use App\Repositories\Interfaces\PermissionRepositoryInterface;
use App\Repositories\Interfaces\RoleRepositoryInterface;
use App\Repositories\Interfaces\StateRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\ServiceProvider;

/**
 * Class AppServiceProvider
 * @package App\Providers
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * @var string[]
     */
    protected $classes = [
        //Repositories
        UserRepositoryInterface::class => EloquentUserRepository::class,
        RoleRepositoryInterface::class => EloquentRoleRepository::class,
        PermissionRepositoryInterface::class => EloquentPermissionRepository::class,
        CountryRepositoryInterface::class => EloquentCountryRepository::class,
        StateRepositoryInterface::class => EloquentStateRepository::class,
        CityRepositoryInterface::class => EloquentCityRepository::class,
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
