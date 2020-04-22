<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
//        if ($request->user()->can('create-tasks')) {
//            dd('puede crear');
//        }

        $user = $request->user();
//        dd($user->hasRole('super-admin')); //will return true, if user has role
//        dd($user->givePermissionsTo('all-actions')); // will return permission, if not null
//        dd($user->can('all-actions')); // will return true, if user has permission

        return view('home');
    }
}
