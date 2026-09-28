<?php
namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\Module;
use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminController extends Controller {
    public function dashboard() {
        return view('admin.dashboard');
    }

    public function index() {
        $programs = \App\Models\TrainingProgram::all();
        return view('admin.programs.index', compact('programs'));
    }

    public function create() {
        return view('admin.programs.create');
    }

    public function store(Request $request) {
        $request->validate(['title' => 'required', 'nc_level' => 'required', 'slots' => 'integer']);
        \App\Models\TrainingProgram::create($request->all());
        return redirect()->route('programs.index')->with('success', 'Program created.');
    }

    public function show(\App\Models\TrainingProgram $program) {
        return view('admin.programs.show', compact('program'));
    }

    public function edit(\App\Models\TrainingProgram $program) {
        return view('admin.programs.edit', compact('program'));
    }

    public function update(Request $request, \App\Models\TrainingProgram $program) {
        $request->validate(['title' => 'required', 'nc_level' => 'required', 'slots' => 'integer']);
        $program->update($request->all());
        return redirect()->route('programs.index')->with('success', 'Program updated.');
    }

    public function destroy(\App\Models\TrainingProgram $program) {
        $program->delete();
        return redirect()->route('programs.index')->with('success', 'Program deleted.');
    }

    public function uploadModule(Request $request) {
        $request->validate([
            'program_id' => 'required|exists:training_programs,id',
            'title' => 'required|string',
            'file' => 'required|file'
        ]);
        
        $path = $request->file('file')->store('modules', 'public');
        
        Module::create([
            'program_id' => $request->program_id,
            'title' => $request->title,
            'description' => $request->description,
            'file_path' => $path,
            'is_visible' => true
        ]);
        return back()->with('success', 'Module uploaded.');
    }

    public function toggleVisibility(Module $module) {
        $module->update(['is_visible' => !$module->is_visible]);
        return back()->with('success', 'Module visibility updated.');
    }

    public function approveEnrollment(Enrollment $enrollment) {
        $enrollment->update(['status' => 'approved']);
        return back()->with('success', 'Enrollment approved.');
    }

    public function issueCertificate(Request $request) {
        $request->validate([
            'trainee_id' => 'required|exists:trainees,id',
            'program_id' => 'required|exists:training_programs,id',
        ]);

        // Generate unique certificate code
        $code = 'AGL-' . strtoupper(Str::random(8)) . '-' . date('Y');

        Certificate::create([
            'trainee_id' => $request->trainee_id,
            'program_id' => $request->program_id,
            'certificate_code' => $code,
            'issued_date' => now(),
        ]);

        return back()->with('success', 'Certificate issued successfully.');
    }
}
