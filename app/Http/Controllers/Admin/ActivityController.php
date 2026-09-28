<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\TraineeActivity;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function index()
    {
        $approved = Enrollment::whereIn('status', ['approved', 'completed'])
            ->with(['trainee', 'program'])
            ->get()
            ->sortBy(fn ($e) => $e->trainee->full_name);

        $activities = TraineeActivity::with(['trainee', 'program'])
            ->latest('activity_date')
            ->limit(100)
            ->get();

        return view('admin.activities.index', compact('approved', 'activities'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'trainee_id' => 'required|exists:trainees,id',
            'program_id' => 'required|exists:training_programs,id',
            'activity_type' => 'required|string|max:50',
            'description' => 'nullable|string|max:255',
        ]);

        TraineeActivity::create($data);

        return redirect()->route('admin.activities.index')->with('success', 'Activity recorded.');
    }
}
