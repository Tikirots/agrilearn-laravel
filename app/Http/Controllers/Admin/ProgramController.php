<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TrainingProgram;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    public function index()
    {
        $programs = TrainingProgram::withCount(['enrollments as enrolled_count' => function ($q) {
            $q->where('status', 'approved');
        }])->latest()->get();

        return view('admin.programs.index', compact('programs'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id' => 'nullable|exists:training_programs,id',
            'title' => 'required|string|max:200',
            'nc_level' => 'required|string|max:50',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'slots' => 'required|integer|min:1',
            'status' => 'required|in:open,ongoing,closed',
        ]);

        TrainingProgram::updateOrCreate(['id' => $data['id'] ?? null], $data);

        return redirect()->route('admin.programs.index')
            ->with('success', !empty($data['id']) ? 'Program updated.' : 'Program created.');
    }

    public function destroy(TrainingProgram $program)
    {
        $program->delete(); // cascades to modules/enrollments via FK

        return redirect()->route('admin.programs.index')->with('success', 'Program deleted.');
    }
}
