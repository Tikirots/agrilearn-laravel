@extends('layouts.app')
@section('title', 'Trainee Activities')
@section('content')
<h3 class="mb-3"><i class="fa-solid fa-clipboard-list"></i> Trainee Activity Recording</h3>

<div class="row">
  <div class="col-lg-4 mb-3">
    <div class="card border-0 shadow-sm p-3">
      <h6 class="fw-semibold mb-3">Record New Activity</h6>
      <form method="POST" action="{{ route('admin.activities.store') }}">
        @csrf
        <div class="mb-2">
          <label class="form-label">Trainee / Program</label>
          <select name="trainee_program" class="form-select" required onchange="const [t,p]=this.value.split('|');document.getElementById('tid').value=t;document.getElementById('pid').value=p;">
            <option value="">Select trainee</option>
            @foreach($approved as $a)
              <option value="{{ $a->trainee_id }}|{{ $a->program_id }}">{{ $a->trainee->full_name }} &mdash; {{ $a->program->title }}</option>
            @endforeach
          </select>
          <input type="hidden" name="trainee_id" id="tid">
          <input type="hidden" name="program_id" id="pid">
        </div>
        <div class="mb-2">
          <label class="form-label">Activity Type</label>
          <select class="form-select" name="activity_type">
            <option>Attendance</option>
            <option>Module Viewed</option>
            <option>Practical Exercise</option>
            <option>Assessment</option>
            <option>Other</option>
          </select>
        </div>
        <div class="mb-3"><label class="form-label">Notes</label><textarea class="form-control" name="description" rows="2"></textarea></div>
        <button class="btn btn-success w-100"><i class="fa-solid fa-plus"></i> Record</button>
      </form>
    </div>
  </div>
  <div class="col-lg-8">
    <div class="card border-0 shadow-sm">
      <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
          <thead class="table-light"><tr><th>Trainee</th><th>Program</th><th>Activity</th><th>Notes</th><th>Date</th></tr></thead>
          <tbody>
            @forelse($activities as $a)
            <tr>
              <td>{{ $a->trainee->full_name }}</td>
              <td>{{ $a->program->title }}</td>
              <td><span class="badge bg-success-subtle text-success-emphasis">{{ $a->activity_type }}</span></td>
              <td>{{ $a->description }}</td>
              <td>{{ $a->activity_date->format('M j, Y g:i A') }}</td>
            </tr>
            @empty
              <tr><td colspan="5" class="text-center text-muted py-3">No activity recorded yet.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
