<?php

namespace App\Http\Controllers\Third;

use App\Constants\PermissionsConstants;
use App\Http\Controllers\Controller;
use App\Http\Requests\ThirdRequest;
use App\Repositories\Interfaces\CountryRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Class ThirdCreateController
 * @package App\Http\Controllers\Third
 */
class ThirdCreateController extends Controller
{
    /**
     * @return View
     */
    public function create(): View
    {
        if (!$this->hasPermission(PermissionsConstants::THIRD_CREATE)) {
            abort(404);
        }

        return view('thirds.create');
    }

    /**
     * @param ThirdRequest $request
     */
    public function store(ThirdRequest $request)
    {

    }
}
