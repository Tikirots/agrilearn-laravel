@extends('layouts.app')

@section('title', 'Manage Online Exams')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="h4 mb-1 font-weight-bold">Online Examinations</h2>
        <p class="text-muted small mb-0">Create and manage exams attached to learning modules.</p>
    </div>
    @if($modulesWithoutExam->isNotEmpty())
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createExamModal">
            <i class="fa-solid fa-plus me-1"></i> Create New Exam
        </button>
    @endif
</div>

<!-- Program Filter -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('admin.exams.index') }}" class="row g-2 align-items-center">
            <div class="col-md-4">
                <select name="program_id" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">All Programs</option>
                    @foreach($programs as $p)
                        <option value="{{ $p->id }}" {{ $programId == $p->id ? 'selected' : '' }}>
                            {{ $p->title }} ({{ $p->nc_level }})
                        </option>
                    @endforeach
                </select>
            </div>
            @if($programId)
                <div class="col-md-2">
                    <a href="{{ route('admin.exams.index') }}" class="btn btn-light btn-sm w-100">Clear Filter</a>
                </div>
            @endif
        </form>
    </div>
</div>

<!-- Exam List Table -->
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th>Exam Title</th>
                        <th>Program / Module</th>
                        <th>Time Limit</th>
                        <th>Passing Score</th>
                        <th>Questions</th>
                        <th>Attempts</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($exams as $exam)
                        <tr>
                            <td>
                                <strong>{{ $exam->title }}</strong>
                            </td>
                            <td>
                                <span class="badge bg-secondary mb-1">{{ $exam->module->program->title ?? 'N/A' }}</span><br>
                                <small class="text-muted"><i class="fa-solid fa-book me-1"></i>{{ $exam->module->title }}</small>
                            </td>
                            <td><i class="fa-regular fa-clock me-1"></i>{{ $exam->time_limit_minutes }} mins</td>
                            <td><span class="badge bg-info text-dark">{{ $exam->passing_score }}%</span></td>
                            <td><span class="badge bg-light text-dark border">{{ $exam->questions->count() }} questions</span></td>
                            <td><span class="badge bg-light text-dark border">{{ $exam->attempts->count() }} attempts</span></td>
                            <td class="text-end">
                                <a href="{{ route('admin.exams.edit', $exam->id) }}" class="btn btn-sm btn-outline-primary me-1" title="Question Builder">
                                    <i class="fa-solid fa-list-check me-1"></i> Questions
                                </a>
                                <a href="{{ route('admin.exams.results', $exam->id) }}" class="btn btn-sm btn-outline-info me-1" title="View Trainee Results">
                                    <i class="fa-solid fa-chart-bar me-1"></i> Results
                                </a>
                                <form method="POST" action="{{ route('admin.exams.destroy', $exam->id) }}" class="d-inline" onsubmit="return confirm('Delete this exam?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                <i class="fa-solid fa-file-pen fa-2x mb-2 text-secondary"></i>
                                <p class="mb-0">No exams created yet.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Create Exam Modal -->
@if($modulesWithoutExam->isNotEmpty())
<div class="modal fade" id="createExamModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('admin.exams.store') }}" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold">Create Exam for Module</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label font-weight-bold">Select Module</label>
                    <select name="module_id" class="form-select" required>
                        <option value="">-- Choose Module --</option>
                        @foreach($modulesWithoutExam as $mod)
                            <option value="{{ $mod->id }}">
                                [{{ $mod->program->title ?? 'Program' }}] {{ $mod->title }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label font-weight-bold">Exam Title</label>
                    <input type="text" name="title" class="form-control" placeholder="e.g. Module 1 Mastery Quiz" required>
                </div>
                <div class="row g-2">
                    <div class="col-md-6 mb-3">
                        <label class="form-label font-weight-bold">Time Limit (Minutes)</label>
                        <input type="number" name="time_limit_minutes" class="form-control" value="30" min="1" max="180" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label font-weight-bold">Passing Score (%)</label>
                        <input type="number" name="passing_score" class="form-control" value="70" min="1" max="100" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary btn-sm">Create Exam & Add Questions</button>
            </div>
        </form>
    </div>
</div>
@endif
@endsection
