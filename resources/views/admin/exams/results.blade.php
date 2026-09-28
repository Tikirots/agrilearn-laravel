@extends('layouts.app')

@section('title', 'Exam Results - ' . $exam->title)

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.exams.index') }}" class="btn btn-outline-secondary btn-sm mb-2">
        <i class="fa-solid fa-arrow-left me-1"></i> Back to Exams
    </a>
    <h2 class="h4 mb-1 font-weight-bold">Trainee Exam Results</h2>
    <p class="text-muted small mb-0">Exam: <strong>{{ $exam->title }}</strong> | Module: {{ $exam->module->title }} | Passing Score: {{ $exam->passing_score }}%</p>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th>Trainee Name</th>
                        <th>Email</th>
                        <th>Score</th>
                        <th>Result Status</th>
                        <th>Date Taken</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($exam->attempts as $attempt)
                        <tr>
                            <td class="font-weight-bold">
                                {{ $attempt->trainee->full_name ?? $attempt->trainee->user->name ?? 'N/A' }}
                            </td>
                            <td>{{ $attempt->trainee->user->email ?? 'N/A' }}</td>
                            <td>
                                <strong class="h6 mb-0">{{ $attempt->score }}%</strong>
                                <small class="text-muted">({{ $attempt->score * $attempt->total_questions / 100 }}/{{ $attempt->total_questions }})</small>
                            </td>
                            <td>
                                @if($attempt->passed)
                                    <span class="badge bg-success"><i class="fa-solid fa-check-circle me-1"></i> PASSED</span>
                                @else
                                    <span class="badge bg-danger"><i class="fa-solid fa-times-circle me-1"></i> FAILED</span>
                                @endif
                            </td>
                            <td><small class="text-muted">{{ $attempt->date_taken ? $attempt->date_taken->format('M d, Y h:i A') : $attempt->created_at->format('M d, Y h:i A') }}</small></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                <i class="fa-solid fa-user-clock fa-2x mb-2 text-secondary"></i>
                                <p class="mb-0">No trainees have taken this exam yet.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
