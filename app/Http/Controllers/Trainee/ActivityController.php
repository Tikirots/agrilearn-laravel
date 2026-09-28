<?php

namespace App\Http\Controllers\Trainee;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ActivityController extends Controller
{
    public function index()
    {
        $trainee = Auth::user()->trainee;
        $activities = $trainee->activities()->with('program')->latest('activity_date')->get();

        return view('trainee.activities', compact('activities'));
    }
}
