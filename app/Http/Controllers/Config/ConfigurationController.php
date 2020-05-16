<?php

namespace App\Http\Controllers\Config;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

/**
 * Class ConfigurationController
 * @package App\Http\Controllers\Config
 */
class ConfigurationController extends Controller
{
    /**
     * @return View
     */
    public function index():View
    {
        return view('configuration.index');
    }
}
