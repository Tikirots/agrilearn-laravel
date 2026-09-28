@extends('layouts.app')
@section('title', 'My Programs')
@section('content')
<h3 class="mb-3"><i class="fa-solid fa-list-check"></i> My Programs</h3>
<div class="row g-3">
  @forelse($enrollments as $e)
    <div class="col-md-6">
      <div class="card border-0 shadow-sm p-3">
        <div class="d-flex justify-content-between">
          <h5>{{ $e->program->title }}</h5>
          <span class="badge bg-{{ ['pending'=>'warning','approved'=>'success','rejected'=>'danger','completed'=>'secondary'][$e->status] }}">{{ ucfirst($e->status) }}</span>
        </div>
        <p class="text-muted small">{{ $e->program->nc_level }}</p>
        <p class="small">{{ $e->program->description }}</p>
        <p class="small mb-0"><i class="fa-regular fa-calendar"></i> {{ format_date($e->program->start_date) }} &ndash; {{ format_date($e->program->end_date) }}</p>
        @if($e->status==='approved')
          <a href="{{ route('trainee.modules.index', ['program_id' => $e->program_id]) }}" class="btn btn-sm btn-success mt-3">View Modules</a>
        @elseif($e->status==='completed')
          <a href="{{ route('trainee.certificates.index') }}" class="btn btn-sm btn-outline-success mt-3">View Certificate</a>
        @endif
      </div>
    </div>
  @empty
    <div class="col-12"><p class="text-muted text-center py-4">No enrollments yet. <a href="{{ route('trainee.enroll.index') }}">Browse programs</a>.</p></div>
  @endforelse
</div>
@endsection
