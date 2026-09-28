<?php

namespace App\Http\Controllers\Trainee;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $trainee = Auth::user()->trainee;
        $enrollments = $trainee->enrollments()->with('program')->latest('enrolled_at')->get();
        $certCount = $trainee->certificates()->count();

        return view('trainee.dashboard', compact('trainee', 'enrollments', 'certCount'));
    }
}
