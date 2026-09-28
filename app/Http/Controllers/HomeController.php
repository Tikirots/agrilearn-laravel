<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Redirect to the correct dashboard based on user role.
     */
    public function index()
    {
        $user = Auth::user();

        if (in_array($user->role, ['admin', 'trainer'])) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('trainee.dashboard');
    }
}
