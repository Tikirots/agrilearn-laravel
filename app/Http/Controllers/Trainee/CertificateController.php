<?php

namespace App\Http\Controllers\Trainee;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CertificateController extends Controller
{
    public function index()
    {
        $trainee = Auth::user()->trainee;
        $certificates = $trainee->certificates()->with('program')->latest()->get();

        return view('trainee.certificates', compact('trainee', 'certificates'));
    }
}
