@extends('layouts.app')

@section('title', 'Exam Builder - ' . $exam->title)

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.exams.index') }}" class="btn btn-outline-secondary btn-sm mb-2">
        <i class="fa-solid fa-arrow-left me-1"></i> Back to Exams
    </a>
    <h2 class="h4 mb-1 font-weight-bold">Exam Question Builder</h2>
    <p class="text-muted small mb-0">Module: <strong>{{ $exam->module->title }}</strong> ({{ $exam->module->program->title ?? '' }})</p>
</div>

<div class="row g-4">
    <!-- Exam Settings -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white font-weight-bold">Exam Settings</div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.exams.update', $exam->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Exam Title</label>
                        <input type="text" name="title" class="form-control form-control-sm" value="{{ $exam->title }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Time Limit (Minutes)</label>
                        <input type="number" name="time_limit_minutes" class="form-control form-control-sm" value="{{ $exam->time_limit_minutes }}" min="1" max="180" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Passing Score (%)</label>
                        <input type="number" name="passing_score" class="form-control form-control-sm" value="{{ $exam->passing_score }}" min="1" max="100" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm w-100">Update Settings</button>
                </form>
            </div>
        </div>

        <!-- Add Question Form -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white font-weight-bold"><i class="fa-solid fa-plus-circle me-1"></i> Add New Question</div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.exams.questions.store', $exam->id) }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Question Text</label>
                        <textarea name="question_text" class="form-control form-control-sm" rows="3" placeholder="Enter question..." required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Question Type</label>
                        <select name="type" id="qTypeSelect" class="form-select form-select-sm" onchange="toggleQuestionOptions(this.value)">
                            <option value="multiple_choice">Multiple Choice</option>
                            <option value="true_false">True / False</option>
                        </select>
                    </div>

                    <!-- Multiple Choice Options -->
                    <div id="mcOptionsWrap">
                        <label class="form-label font-weight-bold">Choices (Enter 2 to 4 choices)</label>
                        <input type="text" name="choices[]" class="form-control form-control-sm mb-1" placeholder="Choice A" id="mcChoiceA">
                        <input type="text" name="choices[]" class="form-control form-control-sm mb-1" placeholder="Choice B" id="mcChoiceB">
                        <input type="text" name="choices[]" class="form-control form-control-sm mb-1" placeholder="Choice C">
                        <input type="text" name="choices[]" class="form-control form-control-sm mb-2" placeholder="Choice D">
                        <div class="mb-3">
                            <label class="form-label font-weight-bold">Correct Answer</label>
                            <input type="text" name="correct_answer" id="mcCorrectAns" class="form-control form-control-sm" placeholder="Must match exact correct choice text" required>
                        </div>
                    </div>

                    <!-- True / False Options -->
                    <div id="tfOptionsWrap" style="display: none;">
                        <div class="mb-3">
                            <label class="form-label font-weight-bold">Correct Answer</label>
                            <select name="correct_answer" id="tfCorrectAns" class="form-select form-select-sm" disabled>
                                <option value="True">True</option>
                                <option value="False">False</option>
                            </select>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success btn-sm w-100"><i class="fa-solid fa-plus me-1"></i> Save Question</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Questions List -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <span class="font-weight-bold">Questions List ({{ $exam->questions->count() }})</span>
            </div>
            <div class="card-body">
                @forelse($exam->questions as $index => $q)
                    <div class="border rounded p-3 mb-3 bg-light">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h6 class="font-weight-bold mb-0">Q{{ $index + 1 }}. {{ $q->question_text }}</h6>
                            <form method="POST" action="{{ route('admin.exams.questions.destroy', $q->id) }}" class="ms-2" onsubmit="return confirm('Delete question?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger py-0 px-2"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </div>
                        <div class="small mb-2">
                            <span class="badge bg-secondary me-2">{{ str_replace('_', ' ', strtoupper($q->type)) }}</span>
                            <span class="text-success font-weight-bold"><i class="fa-solid fa-check me-1"></i> Correct Answer: {{ $q->correct_answer }}</span>
                        </div>
                        @if($q->choices && count($q->choices) > 0)
                            <div class="row g-2">
                                @foreach($q->choices as $choice)
                                    <div class="col-md-6">
                                        <div class="bg-white p-2 rounded border small {{ strtolower(trim($choice)) === strtolower(trim($q->correct_answer)) ? 'border-success text-success fw-bold' : '' }}">
                                            {{ $choice }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="text-center py-5 text-muted">
                        <i class="fa-solid fa-circle-question fa-3x mb-2 text-secondary"></i>
                        <p class="mb-0">No questions added yet. Use the form on the left to add questions.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function toggleQuestionOptions(type) {
    const mcWrap = document.getElementById('mcOptionsWrap');
    const tfWrap = document.getElementById('tfOptionsWrap');
    const mcCorrect = document.getElementById('mcCorrectAns');
    const tfCorrect = document.getElementById('tfCorrectAns');

    if (type === 'true_false') {
        mcWrap.style.display = 'none';
        tfWrap.style.display = 'block';
        mcCorrect.disabled = true;
        tfCorrect.disabled = false;
    } else {
        mcWrap.style.display = 'block';
        tfWrap.style.display = 'none';
        mcCorrect.disabled = false;
        tfCorrect.disabled = true;
    }
}
</script>
@endpush
@endsection
