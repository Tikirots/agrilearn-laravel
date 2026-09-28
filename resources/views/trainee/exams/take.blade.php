@extends('layouts.app')

@section('title', 'Taking Exam - ' . $exam->title)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 sticky-top bg-light p-3 rounded shadow-sm border" style="top: 10px; z-index: 1020;">
    <div>
        <h5 class="font-weight-bold mb-0">{{ $exam->title }}</h5>
        <small class="text-muted">Total Questions: {{ $exam->questions->count() }}</small>
    </div>
    <div class="text-end">
        <span class="text-muted small d-block">Time Remaining:</span>
        <span class="badge bg-danger h5 mb-0 px-3 py-2" id="examTimer">
            <i class="fa-regular fa-clock me-1"></i> <span id="timerText">00:00</span>
        </span>
    </div>
</div>

<form id="examSubmitForm" method="POST" action="{{ route('trainee.exams.submit', $exam->id) }}">
    @csrf
    <div class="row justify-content-center">
        <div class="col-lg-10">
            @foreach($exam->questions as $index => $q)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white font-weight-bold">
                        Question {{ $index + 1 }} of {{ $exam->questions->count() }}
                    </div>
                    <div class="card-body">
                        <p class="h6 font-weight-bold mb-3">{{ $q->question_text }}</p>

                        @if($q->choices && count($q->choices) > 0)
                            <div class="row g-2">
                                @foreach($q->choices as $cIndex => $choice)
                                    <div class="col-12">
                                        <div class="form-check p-3 border rounded bg-white hover-shadow">
                                            <input class="form-check-input" type="radio" name="answers[{{ $q->id }}]" id="q_{{ $q->id }}_{{ $cIndex }}" value="{{ $choice }}">
                                            <label class="form-check-label w-100 cursor-pointer text-dark" for="q_{{ $q->id }}_{{ $cIndex }}">
                                                {{ $choice }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="row g-2">
                                <div class="col-6">
                                    <div class="form-check p-3 border rounded bg-white">
                                        <input class="form-check-input" type="radio" name="answers[{{ $q->id }}]" id="q_{{ $q->id }}_true" value="True">
                                        <label class="form-check-label w-100 cursor-pointer font-weight-bold text-success" for="q_{{ $q->id }}_true">True</label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-check p-3 border rounded bg-white">
                                        <input class="form-check-input" type="radio" name="answers[{{ $q->id }}]" id="q_{{ $q->id }}_false" value="False">
                                        <label class="form-check-label w-100 cursor-pointer font-weight-bold text-danger" for="q_{{ $q->id }}_false">False</label>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach

            <div class="card border-0 shadow-sm mb-5">
                <div class="card-body text-center p-4">
                    <button type="submit" class="btn btn-success btn-lg px-5 font-weight-bold" onclick="return confirm('Are you sure you want to submit your exam answers?')">
                        <i class="fa-solid fa-paper-plane me-2"></i> Submit Exam
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        let totalSeconds = {{ $exam->time_limit_minutes * 60 }};
        const timerText = document.getElementById('timerText');
        const form = document.getElementById('examSubmitForm');

        const interval = setInterval(function () {
            totalSeconds--;
            if (totalSeconds <= 0) {
                clearInterval(interval);
                timerText.innerText = "00:00";
                alert("Time is up! Your exam will now be submitted automatically.");
                form.submit();
                return;
            }

            let mins = Math.floor(totalSeconds / 60);
            let secs = totalSeconds % 60;
            timerText.innerText = (mins < 10 ? '0' : '') + mins + ':' + (secs < 10 ? '0' : '') + secs;
        }, 1000);
    });
</script>
@endpush
@endsection
