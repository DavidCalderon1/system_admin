<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

/**
 * Class Controller
 * @package App\Http\Controllers
 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public const ALL_ACTIONS = 'all-actions';

    /**
     * @param array $data
     * @param string $message
     * @param int $code
     * @return JsonResponse
     */
    public function response(int $code = 200, $message = '', array $data = []): JsonResponse
    {
        if ($code === 401) {
            return response()->json(['errors' => [
                'title' => 'Unauthorized',
                'message' => 'Access denied.',
                'code' => $code
            ]], $code);
        } else if ($code === 404) {
            return response()->json(['errors' => [
                'title' => 'Not found',
                'message' => $message,
                'code' => $code
            ]], $code);
        }

        return response()->json(['data' => $data, 'message' => $message], $code);
    }

    /**
     * valida que tenga permisos para acceder al modulo y si es superadmin tiene acceso
     *
     * @param string $slug
     * @param $request
     * @return bool
     */
    public function hasPermission(string $slug): bool
    {
        return Auth::user()->can($slug) || Auth::user()->hasRole('super-admin');
    }
}
