<?php

namespace App\Http\Controllers\Trainee;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Models\TraineeActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExamController extends Controller
{
    public function index()
    {
        $trainee = Auth::user()->trainee;

        $approvedProgramIds = $trainee->enrollments()
            ->whereIn('status', ['approved', 'completed'])
            ->pluck('program_id');

        $exams = Exam::whereHas('module', function ($m) use ($approvedProgramIds) {
            $m->whereIn('program_id', $approvedProgramIds)->where('is_visible', true);
        })
        ->with(['module.program', 'questions'])
        ->with(['attempts' => function ($q) use ($trainee) {
            $q->where('trainee_id', $trainee->id)->latest('date_taken');
        }])
        ->get();

        return view('trainee.exams.index', compact('exams'));
    }

    public function show(Exam $exam)
    {
        $trainee = Auth::user()->trainee;

        $enrolled = $trainee->enrollments()
            ->where('program_id', $exam->module->program_id)
            ->whereIn('status', ['approved', 'completed'])
            ->exists();

        if (!$enrolled || !$exam->module->is_visible) {
            return redirect()->route('trainee.exams.index')->with('error', 'Access denied.');
        }

        $latestAttempt = ExamAttempt::where('exam_id', $exam->id)
            ->where('trainee_id', $trainee->id)
            ->latest('date_taken')
            ->first();

        return view('trainee.exams.show', compact('exam', 'latestAttempt'));
    }

    public function take(Exam $exam)
    {
        $trainee = Auth::user()->trainee;

        $enrolled = $trainee->enrollments()
            ->where('program_id', $exam->module->program_id)
            ->whereIn('status', ['approved', 'completed'])
            ->exists();

        if (!$enrolled || !$exam->module->is_visible) {
            return redirect()->route('trainee.exams.index')->with('error', 'Access denied.');
        }

        $exam->load('questions');

        if ($exam->questions->isEmpty()) {
            return redirect()->route('trainee.exams.show', $exam->id)
                ->with('error', 'This exam currently has no questions.');
        }

        return view('trainee.exams.take', compact('exam'));
    }

    public function submit(Request $request, Exam $exam)
    {
        $trainee = Auth::user()->trainee;
        $exam->load('questions');

        $userAnswers = $request->input('answers', []);
        $correctCount = 0;
        $totalQuestions = $exam->questions->count();

        foreach ($exam->questions as $question) {
            $submitted = trim($userAnswers[$question->id] ?? '');
            if ($submitted !== '' && strtolower($submitted) === strtolower(trim($question->correct_answer))) {
                $correctCount++;
            }
        }

        $percentageScore = $totalQuestions > 0 ? (int) round(($correctCount / $totalQuestions) * 100) : 0;
        $passed = $percentageScore >= $exam->passing_score;

        $attempt = ExamAttempt::create([
            'exam_id' => $exam->id,
            'trainee_id' => $trainee->id,
            'score' => $percentageScore,
            'total_questions' => $totalQuestions,
            'passed' => $passed,
            'answers_json' => $userAnswers,
            'date_taken' => now(),
        ]);

        // Log to TraineeActivity
        TraineeActivity::create([
            'trainee_id' => $trainee->id,
            'program_id' => $exam->module->program_id,
            'module_id' => $exam->module_id,
            'activity_type' => 'Exam Attempted',
            'description' => "Exam: {$exam->title} - Score: {$percentageScore}% (" . ($passed ? 'PASSED' : 'FAILED') . ")",
            'activity_date' => now(),
        ]);

        return redirect()->route('trainee.exams.result', $attempt->id)
            ->with('success', 'Exam submitted successfully.');
    }

    public function result(ExamAttempt $attempt)
    {
        $trainee = Auth::user()->trainee;

        if ($attempt->trainee_id !== $trainee->id) {
            abort(403);
        }

        $attempt->load(['exam.module.program', 'exam.questions']);

        return view('trainee.exams.result', compact('attempt'));
    }
}
