<?php

namespace App\Http\Controllers\Trainee;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class MyEnrollmentController extends Controller
{
    public function index()
    {
        $trainee = Auth::user()->trainee;
        $enrollments = $trainee->enrollments()->with('program')->latest('enrolled_at')->get();

        return view('trainee.my_enrollments', compact('enrollments'));
    }
}
