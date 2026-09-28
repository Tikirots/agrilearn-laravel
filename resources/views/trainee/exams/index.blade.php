@extends('layouts.app')

@section('title', 'Online Examinations')

@section('content')
<div class="mb-4">
    <h2 class="h4 mb-1 font-weight-bold">Online Examinations</h2>
    <p class="text-muted small mb-0">Take module quizzes and exams to test your knowledge.</p>
</div>

<div class="row g-3">
    @forelse($exams as $exam)
        @php
            $latest = $exam->attempts->first();
        @endphp
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body d-flex flex-column">
                    <div class="mb-2">
                        <span class="badge bg-secondary mb-1">{{ $exam->module->program->title ?? '' }}</span>
                        <h5 class="card-title font-weight-bold mb-1">{{ $exam->title }}</h5>
                        <p class="card-text text-muted small mb-2"><i class="fa-solid fa-book me-1"></i> Module: {{ $exam->module->title }}</p>
                    </div>

                    <div class="bg-light p-2 rounded mb-3 small">
                        <div class="d-flex justify-content-between mb-1">
                            <span><i class="fa-regular fa-clock me-1"></i> Time Limit:</span>
                            <strong>{{ $exam->time_limit_minutes }} mins</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span><i class="fa-solid fa-bullseye me-1"></i> Passing Score:</span>
                            <strong>{{ $exam->passing_score }}%</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span><i class="fa-solid fa-circle-question me-1"></i> Questions:</span>
                            <strong>{{ $exam->questions->count() }}</strong>
                        </div>
                    </div>

                    <div class="mt-auto pt-2 border-top">
                        @if($latest)
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="small text-muted">Latest Result:</span>
                                @if($latest->passed)
                                    <span class="badge bg-success"><i class="fa-solid fa-check-circle me-1"></i> Passed ({{ $latest->score }}%)</span>
                                @else
                                    <span class="badge bg-danger"><i class="fa-solid fa-times-circle me-1"></i> Failed ({{ $latest->score }}%)</span>
                                @endif
                            </div>
                            <a href="{{ route('trainee.exams.show', $exam->id) }}" class="btn btn-outline-primary btn-sm w-100">Retake / View Exam</a>
                        @else
                            <a href="{{ route('trainee.exams.show', $exam->id) }}" class="btn btn-primary btn-sm w-100"><i class="fa-solid fa-play me-1"></i> Start Exam</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center py-5 bg-white rounded shadow-sm">
            <i class="fa-solid fa-file-pen fa-3x text-secondary mb-2"></i>
            <h5 class="font-weight-bold">No Exams Available</h5>
            <p class="text-muted mb-0">There are no active online exams for your enrolled programs at this time.</p>
        </div>
    @endforelse
</div>
@endsection
