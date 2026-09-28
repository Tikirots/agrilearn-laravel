<?php

namespace App\Http\Controllers\Trainee;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\TrainingProgram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnrollController extends Controller
{
    public function index()
    {
        $trainee = Auth::user()->trainee;

        $appliedProgramIds = $trainee->enrollments()->pluck('program_id');

        $programs = TrainingProgram::where('status', '!=', 'closed')
            ->whereNotIn('id', $appliedProgramIds)
            ->withCount(['enrollments as enrolled_count' => fn ($q) => $q->where('status', 'approved')])
            ->orderBy('start_date')
            ->get();

        return view('trainee.enroll', compact('programs'));
    }

    public function store(Request $request)
    {
        $trainee = Auth::user()->trainee;
        $programId = (int) $request->input('program_id');

        $exists = Enrollment::where('trainee_id', $trainee->id)->where('program_id', $programId)->exists();

        if ($exists) {
            return redirect()->route('trainee.enroll.index')->with('warning', 'You already applied to this program.');
        }

        Enrollment::create([
            'trainee_id' => $trainee->id,
            'program_id' => $programId,
            'status' => 'pending',
        ]);

        return redirect()->route('trainee.enroll.index')->with('success', 'Application submitted! Please wait for admin approval.');
    }
}
