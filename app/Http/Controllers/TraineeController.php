<?php
namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\Enrollment;
use App\Models\TraineeActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TraineeController extends Controller {
    public function dashboard() {
        $trainee = Auth::user()->trainee;
        $activeEnrollments = Enrollment::where('trainee_id', $trainee->id)->with('program')->get();
        return view('trainee.dashboard', compact('activeEnrollments', 'trainee'));
    }

    public function apply(\App\Models\TrainingProgram $program) {
        $trainee = Auth::user()->trainee;
        Enrollment::firstOrCreate(
            ['trainee_id' => $trainee->id, 'program_id' => $program->id],
            ['status' => 'pending']
        );
        return back()->with('success', 'Application submitted successfully.');
    }

    public function viewModule(Module $module) {
        $trainee = Auth::user()->trainee;
        
        // Ensure module is visible OR user is an admin
        if (!$module->is_visible) {
            abort(403, 'This module is not currently available.');
        }

        // Check if trainee has an approved enrollment for this program
        $enrollment = Enrollment::where('trainee_id', $trainee->id)
                                ->where('program_id', $module->program_id)
                                ->where('status', 'approved')
                                ->first();
        if (!$enrollment) {
            abort(403, 'You are not enrolled in this program.');
        }

        // Log Activity
        TraineeActivity::create([
            'trainee_id' => $trainee->id,
            'activity_type' => 'module_view',
            'reference_id' => $module->id,
            'details' => 'Viewed module: ' . $module->title
        ]);

        return view('trainee.modules.view', compact('module'));
    }
}
