@extends('layouts.app')
@section('title', 'My Dashboard')
@section('content')
<h3 class="mb-1">Welcome, {{ $trainee->full_name }} <i class="fa-solid fa-seedling text-success"></i></h3>
<p class="text-muted mb-4">Masaganang Bukid Agricultural Learning Center</p>

<div class="row g-3 mb-4">
  <div class="col-6 col-md-3">
    <div class="card stat-card border-0 shadow-sm text-center p-3">
      <i class="fa-solid fa-list-check fa-lg text-success mb-2"></i>
      <h4>{{ $enrollments->count() }}</h4><small class="text-muted">Total Enrollments</small>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="card stat-card border-0 shadow-sm text-center p-3">
      <i class="fa-solid fa-certificate fa-lg text-success mb-2"></i>
      <h4>{{ $certCount }}</h4><small class="text-muted">Certificates</small>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <a href="{{ route('trainee.enroll.index') }}" class="text-decoration-none">
      <div class="card border-0 shadow-sm text-center p-3 quick-link h-100 d-flex justify-content-center">
        <i class="fa-solid fa-pen-to-square fa-lg text-success mb-2"></i><span>Enroll in a Program</span>
      </div>
    </a>
  </div>
  <div class="col-6 col-md-3">
    <a href="{{ route('trainee.modules.index') }}" class="text-decoration-none">
      <div class="card border-0 shadow-sm text-center p-3 quick-link h-100 d-flex justify-content-center">
        <i class="fa-solid fa-book-open fa-lg text-success mb-2"></i><span>View Learning Modules</span>
      </div>
    </a>
  </div>
</div>

<div class="card border-0 shadow-sm">
  <div class="card-header bg-white fw-semibold">My Enrollments</div>
  <div class="table-responsive">
    <table class="table mb-0 align-middle">
      <thead class="table-light"><tr><th>Program</th><th>Schedule</th><th>Status</th></tr></thead>
      <tbody>
        @forelse($enrollments as $e)
        <tr>
          <td>{{ $e->program->title }}</td>
          <td>{{ format_date($e->program->start_date) }} &ndash; {{ format_date($e->program->end_date) }}</td>
          <td><span class="badge bg-{{ ['pending'=>'warning','approved'=>'success','rejected'=>'danger','completed'=>'secondary'][$e->status] }}">{{ ucfirst($e->status) }}</span></td>
        </tr>
        @empty
          <tr><td colspan="3" class="text-center text-muted py-3">You haven't enrolled in any program yet. <a href="{{ route('trainee.enroll.index') }}">Enroll now</a>.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
