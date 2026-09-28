<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamQuestion;
use App\Models\Module;
use App\Models\TrainingProgram;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    public function index(Request $request)
    {
        $programs = TrainingProgram::with(['modules.exam'])->get();
        $programId = $request->query('program_id');

        $exams = Exam::with(['module.program', 'questions', 'attempts'])
            ->when($programId, function ($q) use ($programId) {
                $q->whereHas('module', function ($m) use ($programId) {
                    $m->where('program_id', $programId);
                });
            })
            ->latest()
            ->get();

        $modulesWithoutExam = Module::whereDoesntHave('exam')->with('program')->get();

        return view('admin.exams.index', compact('exams', 'programs', 'programId', 'modulesWithoutExam'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'module_id' => 'required|exists:modules,id|unique:exams,module_id',
            'title' => 'required|string|max:255',
            'time_limit_minutes' => 'required|integer|min:1|max:180',
            'passing_score' => 'required|integer|min:1|max:100',
        ]);

        $exam = Exam::create([
            'module_id' => $request->module_id,
            'title' => $request->title,
            'time_limit_minutes' => $request->time_limit_minutes,
            'passing_score' => $request->passing_score,
        ]);

        return redirect()->route('admin.exams.edit', $exam->id)
            ->with('success', 'Exam created! You can now add questions below.');
    }

    public function edit(Exam $exam)
    {
        $exam->load(['module.program', 'questions']);

        return view('admin.exams.edit', compact('exam'));
    }

    public function update(Request $request, Exam $exam)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'time_limit_minutes' => 'required|integer|min:1|max:180',
            'passing_score' => 'required|integer|min:1|max:100',
        ]);

        $exam->update([
            'title' => $request->title,
            'time_limit_minutes' => $request->time_limit_minutes,
            'passing_score' => $request->passing_score,
        ]);

        return back()->with('success', 'Exam details updated.');
    }

    public function storeQuestion(Request $request, Exam $exam)
    {
        $request->validate([
            'question_text' => 'required|string',
            'type' => 'required|in:multiple_choice,true_false',
            'choices' => 'nullable|array',
            'correct_answer' => 'required|string',
        ]);

        $choices = null;
        if ($request->type === 'multiple_choice') {
            $choices = array_values(array_filter($request->choices ?? [], fn($c) => trim($c) !== ''));
        } else {
            $choices = ['True', 'False'];
        }

        ExamQuestion::create([
            'exam_id' => $exam->id,
            'question_text' => $request->question_text,
            'type' => $request->type,
            'choices' => $choices,
            'correct_answer' => $request->correct_answer,
        ]);

        return back()->with('success', 'Question added successfully.');
    }

    public function destroyQuestion(ExamQuestion $question)
    {
        $examId = $question->exam_id;
        $question->delete();

        return back()->with('success', 'Question deleted.');
    }

    public function destroy(Exam $exam)
    {
        $exam->delete();

        return redirect()->route('admin.exams.index')->with('success', 'Exam deleted successfully.');
    }

    public function results(Exam $exam)
    {
        $exam->load(['module.program', 'attempts.trainee.user']);

        return view('admin.exams.results', compact('exam'));
    }
}
