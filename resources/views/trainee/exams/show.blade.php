@extends('layouts.app')

@section('title', 'Exam Details - ' . $exam->title)

@section('content')
<div class="mb-4">
    <a href="{{ route('trainee.exams.index') }}" class="btn btn-outline-secondary btn-sm mb-2">
        <i class="fa-solid fa-arrow-left me-1"></i> Back to Exams
    </a>
    <h2 class="h4 mb-1 font-weight-bold">{{ $exam->title }}</h2>
    <p class="text-muted small mb-0">Module: <strong>{{ $exam->module->title }}</strong> ({{ $exam->module->program->title ?? '' }})</p>
</div>

<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card border-0 shadow-sm text-center">
            <div class="card-body p-4">
                <div class="mb-4">
                    <span class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle mb-3" style="width: 70px; height: 70px;">
                        <i class="fa-solid fa-file-signature fa-2x"></i>
                    </span>
                    <h4 class="font-weight-bold mb-1">{{ $exam->title }}</h4>
                    <p class="text-muted small">Please read the instructions carefully before starting.</p>
                </div>

                <div class="row g-2 mb-4 text-start">
                    <div class="col-6">
                        <div class="bg-light p-3 rounded text-center">
                            <div class="text-muted small"><i class="fa-regular fa-clock me-1"></i> Time Limit</div>
                            <div class="h5 font-weight-bold mb-0 mt-1">{{ $exam->time_limit_minutes }} Minutes</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="bg-light p-3 rounded text-center">
                            <div class="text-muted small"><i class="fa-solid fa-bullseye me-1"></i> Passing Score</div>
                            <div class="h5 font-weight-bold mb-0 mt-1 text-primary">{{ $exam->passing_score }}%</div>
                        </div>
                    </div>
                </div>

                @if($latestAttempt)
                    <div class="alert alert-info text-start mb-4">
                        <h6 class="font-weight-bold mb-1"><i class="fa-solid fa-history me-1"></i> Your Previous Attempt:</h6>
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <span>Score: <strong>{{ $latestAttempt->score }}%</strong></span>
                            @if($latestAttempt->passed)
                                <span class="badge bg-success">PASSED</span>
                            @else
                                <span class="badge bg-danger">FAILED</span>
                            @endif
                        </div>
                    </div>
                @endif

                <div class="alert alert-warning small text-start mb-4">
                    <i class="fa-solid fa-triangle-exclamation me-1"></i>
                    Once you click <strong>Start Exam</strong>, the timer will begin. Do not refresh or close the browser page during the exam.
                </div>

                <a href="{{ route('trainee.exams.take', $exam->id) }}" class="btn btn-primary btn-lg w-100 font-weight-bold">
                    <i class="fa-solid fa-play me-2"></i> Start Exam Now
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
