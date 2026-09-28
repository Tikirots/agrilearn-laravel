@extends('layouts.app')

@section('title', 'Exam Result - ' . $attempt->exam->title)

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card border-0 shadow-sm text-center">
            <div class="card-body p-4">
                <div class="mb-4">
                    @if($attempt->passed)
                        <div class="d-inline-flex align-items-center justify-content-center bg-success text-white rounded-circle mb-3" style="width: 80px; height: 80px;">
                            <i class="fa-solid fa-trophy fa-3x"></i>
                        </div>
                        <h3 class="font-weight-bold text-success mb-1">CONGRATULATIONS!</h3>
                        <p class="text-muted">You have successfully passed the exam.</p>
                    @else
                        <div class="d-inline-flex align-items-center justify-content-center bg-danger text-white rounded-circle mb-3" style="width: 80px; height: 80px;">
                            <i class="fa-solid fa-circle-xmark fa-3x"></i>
                        </div>
                        <h3 class="font-weight-bold text-danger mb-1">EXAM FAILED</h3>
                        <p class="text-muted">You did not meet the required passing score.</p>
                    @endif
                </div>

                <div class="bg-light p-4 rounded mb-4">
                    <div class="row text-center">
                        <div class="col-6 border-end">
                            <small class="text-muted d-block mb-1">Your Score</small>
                            <span class="h2 font-weight-bold mb-0 {{ $attempt->passed ? 'text-success' : 'text-danger' }}">
                                {{ $attempt->score }}%
                            </span>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block mb-1">Passing Requirement</small>
                            <span class="h2 font-weight-bold mb-0 text-dark">
                                {{ $attempt->exam->passing_score }}%
                            </span>
                        </div>
                    </div>
                </div>

                <div class="text-start mb-4">
                    <h6 class="font-weight-bold mb-2">Exam Information:</h6>
                    <ul class="list-group list-group-flush border rounded">
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="text-muted">Exam Title</span>
                            <strong>{{ $attempt->exam->title }}</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="text-muted">Module</span>
                            <strong>{{ $attempt->exam->module->title }}</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="text-muted">Date Taken</span>
                            <strong>{{ $attempt->date_taken ? $attempt->date_taken->format('M d, Y h:i A') : $attempt->created_at->format('M d, Y h:i A') }}</strong>
                        </li>
                    </ul>
                </div>

                <div class="d-grid gap-2">
                    <a href="{{ route('trainee.exams.index') }}" class="btn btn-primary font-weight-bold">
                        <i class="fa-solid fa-list me-1"></i> Back to Available Exams
                    </a>
                    <a href="{{ route('trainee.modules.index') }}" class="btn btn-outline-secondary">
                        <i class="fa-solid fa-book-open me-1"></i> Continue Learning Modules
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
