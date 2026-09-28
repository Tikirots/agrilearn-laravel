<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Default redirect (overridden per-role in redirectTo method).
     */
    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Redirect user to the correct dashboard based on their role.
     */
    protected function redirectTo()
    {
        $role = auth()->user()->role;

        if (in_array($role, ['admin', 'trainer'])) {
            return route('admin.dashboard');
        }

        return route('trainee.dashboard');
    }
}
