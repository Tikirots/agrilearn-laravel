@extends('layouts.app')
@section('title', 'Admin Dashboard')
@section('content')
<h3 class="mb-4"><i class="fa-solid fa-gauge"></i> Admin Dashboard</h3>

<div class="row g-3 mb-4">
  <div class="col-6 col-md-4 col-lg-2">
    <div class="card stat-card border-0 shadow-sm text-center p-3">
      <i class="fa-solid fa-users fa-lg text-success mb-2"></i>
      <h4>{{ $counts['trainees'] }}</h4><small class="text-muted">Trainees</small>
    </div>
  </div>
  <div class="col-6 col-md-4 col-lg-2">
    <div class="card stat-card border-0 shadow-sm text-center p-3">
      <i class="fa-solid fa-layer-group fa-lg text-success mb-2"></i>
      <h4>{{ $counts['programs'] }}</h4><small class="text-muted">Programs</small>
    </div>
  </div>
  <div class="col-6 col-md-4 col-lg-2">
    <a href="{{ route('admin.trainees.index') }}" class="text-decoration-none">
      <div class="card stat-card border-0 shadow-sm text-center p-3">
        <i class="fa-solid fa-user-clock fa-lg text-danger mb-2"></i>
        <h4>{{ $counts['pending_acct'] }}</h4><small class="text-muted">Pending Registrations</small>
      </div>
    </a>
  </div>
  <div class="col-6 col-md-4 col-lg-2">
    <div class="card stat-card border-0 shadow-sm text-center p-3">
      <i class="fa-solid fa-hourglass-half fa-lg text-warning mb-2"></i>
      <h4>{{ $counts['pending'] }}</h4><small class="text-muted">Pending Enrollments</small>
    </div>
  </div>
  <div class="col-6 col-md-4 col-lg-2">
    <div class="card stat-card border-0 shadow-sm text-center p-3">
      <i class="fa-solid fa-user-check fa-lg text-success mb-2"></i>
      <h4>{{ $counts['approved'] }}</h4><small class="text-muted">Approved</small>
    </div>
  </div>
  <div class="col-6 col-md-4 col-lg-2">
    <div class="card stat-card border-0 shadow-sm text-center p-3">
      <i class="fa-solid fa-certificate fa-lg text-success mb-2"></i>
      <h4>{{ $counts['certificates'] }}</h4><small class="text-muted">Certificates Issued</small>
    </div>
  </div>
</div>

<div class="row g-3 mb-4">
  <div class="col-md-4">
    <a href="{{ route('admin.enrollments.index') }}" class="text-decoration-none">
      <div class="card border-0 shadow-sm p-3 quick-link"><i class="fa-solid fa-user-check text-success"></i> Review Pending Enrollments</div>
    </a>
  </div>
  <div class="col-md-4">
    <a href="{{ route('admin.programs.index') }}" class="text-decoration-none">
      <div class="card border-0 shadow-sm p-3 quick-link"><i class="fa-solid fa-plus text-success"></i> Manage Training Programs</div>
    </a>
  </div>
  <div class="col-md-4">
    <a href="{{ route('admin.certificates.index') }}" class="text-decoration-none">
      <div class="card border-0 shadow-sm p-3 quick-link"><i class="fa-solid fa-certificate text-success"></i> Generate Certificates</div>
    </a>
  </div>
</div>

<div class="card border-0 shadow-sm">
  <div class="card-header bg-white fw-semibold">Recent Trainee Activity</div>
  <div class="table-responsive">
    <table class="table table-hover mb-0 align-middle">
      <thead class="table-light"><tr><th>Trainee</th><th>Program</th><th>Activity</th><th>Date</th></tr></thead>
      <tbody>
        @forelse($recentActivities as $a)
          <tr>
            <td>{{ $a->trainee->full_name }}</td>
            <td>{{ $a->program->title }}</td>
            <td><span class="badge bg-success-subtle text-success-emphasis">{{ $a->activity_type }}</span> {{ $a->description }}</td>
            <td>{{ $a->activity_date->format('M j, Y g:i A') }}</td>
          </tr>
        @empty
          <tr><td colspan="4" class="text-center text-muted py-3">No activity recorded yet.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
