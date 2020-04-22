<?php

namespace App\Http\Middleware;

use Closure;

/**
 * Class RoleMiddleware
 * @package App\Http\Middleware
 */
class RoleMiddleware
{
    private const ALL_ACTIONS='all-actions';

    /**
     * Handle an incoming request.
     *
     * @param $request
     * @param Closure $next
     * @param $role
     * @param null $permission
     * @return mixed
     */
    public function handle($request, Closure $next, $role, $permission = null)
    {
        if (!$request->user()->hasRole($role)) {
            abort(404);
        }

        if (($permission !== null && $permission !== self::ALL_ACTIONS) && !$request->user()->can($permission)) {
            abort(404);
        }

        return $next($request);
    }
}
