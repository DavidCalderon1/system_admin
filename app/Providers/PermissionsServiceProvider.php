<?php

namespace App\Providers;

use App\Constants\PermissionsConstants;
use App\Models\Permission;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class PermissionsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        try {
            Permission::get()->map(function ($permission) {
                Gate::define($permission->slug, function ($user) use ($permission) {
                    return $user->hasPermissionTo($permission);
                });
            });
        } catch (\Exception $e) {
            report($e);
            return false;
        }
        $superAdminRole = PermissionsConstants::ROLE_ADMIN;

        //Blade directives
        Blade::directive('sessionHasPermission', function ($permission) use ($superAdminRole) {
            return "<?php if( (auth()->check() && auth()->user()->can('{$permission}'))
            || (auth()->check() && auth()->user()->hasRole('{$superAdminRole}'))) : ?>";
        });

        Blade::directive('endsessionHasPermission', function () {
            return "<?php endif; ?>";
        });
    }
}
