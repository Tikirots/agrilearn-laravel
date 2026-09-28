<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\Enrollment;
use App\Models\Trainee;
use App\Models\TraineeActivity;
use App\Models\TrainingProgram;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $counts = [
            'trainees' => Trainee::count(),
            'programs' => TrainingProgram::count(),
            'pending_acct' => User::where('role', 'trainee')->where('status', 'pending')->count(),
            'pending' => Enrollment::where('status', 'pending')->count(),
            'approved' => Enrollment::where('status', 'approved')->count(),
            'certificates' => Certificate::count(),
        ];

        $recentActivities = TraineeActivity::with(['trainee', 'program'])
            ->latest('activity_date')
            ->limit(8)
            ->get();

        return view('admin.dashboard', compact('counts', 'recentActivities'));
    }
}
