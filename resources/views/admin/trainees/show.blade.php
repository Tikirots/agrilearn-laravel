@extends('layouts.app')
@section('title', 'Trainee Record')
@section('content')
<a href="{{ route('admin.trainees.index') }}" class="btn btn-sm btn-outline-secondary mb-3"><i class="fa-solid fa-arrow-left"></i> Back</a>
<div class="row">
  <div class="col-md-4">
    <div class="card border-0 shadow-sm p-3 text-center">
      @if($trainee->photo)
        <img src="{{ asset('storage/photos/' . $trainee->photo) }}" alt="Profile photo" class="rounded-circle mx-auto mb-2" style="width:110px;height:110px;object-fit:cover;">
      @else
        <i class="fa-solid fa-circle-user fa-4x text-success mb-2"></i>
      @endif
      <h5>{{ $trainee->full_name }}</h5>
      <p class="text-muted mb-1">{{ $trainee->user->email }}</p>
      <span class="badge bg-{{ ['pending'=>'warning','active'=>'success','inactive'=>'secondary'][$trainee->user->status] }}">{{ ucfirst($trainee->user->status) }}</span>
      @if($trainee->user->status === 'pending')
        <div class="mt-3">
          <form method="POST" action="{{ route('admin.trainees.status', [$trainee->user, 'approve']) }}" class="d-inline">
            @csrf<button class="btn btn-sm btn-success"><i class="fa-solid fa-check"></i> Approve</button>
          </form>
          <form method="POST" action="{{ route('admin.trainees.status', [$trainee->user, 'reject']) }}" class="d-inline" onsubmit="return confirm('Reject this registration?')">
            @csrf<button class="btn btn-sm btn-danger"><i class="fa-solid fa-xmark"></i> Reject</button>
          </form>
        </div>
      @elseif($trainee->user->status === 'inactive')
        <div class="mt-3">
          <form method="POST" action="{{ route('admin.trainees.status', [$trainee->user, 'reactivate']) }}">
            @csrf<button class="btn btn-sm btn-success"><i class="fa-solid fa-rotate-left"></i> Reactivate</button>
          </form>
        </div>
      @endif
    </div>
    <div class="card border-0 shadow-sm p-3 mt-3">
      <p><strong>Username:</strong> {{ $trainee->user->username }}</p>
      <p><strong>Contact:</strong> {{ $trainee->contact_number ?: '—' }}</p>
      <p><strong>Address:</strong> {{ $trainee->address ?: '—' }}</p>
      <p><strong>Birthdate:</strong> {{ format_date($trainee->birthdate) }}</p>
      <p class="mb-0"><strong>Gender:</strong> {{ $trainee->gender ?: '—' }}</p>
    </div>
  </div>
  <div class="col-md-8">
    <div class="card border-0 shadow-sm mb-3">
      <div class="card-header bg-white fw-semibold">Enrollments</div>
      <div class="table-responsive">
        <table class="table mb-0"><thead class="table-light"><tr><th>Program</th><th>Status</th><th>Date</th></tr></thead>
        <tbody>
          @forelse($enrollments as $e)
            <tr><td>{{ $e->program->title }}</td><td><span class="badge bg-{{ ['pending'=>'warning','approved'=>'success','rejected'=>'danger','completed'=>'secondary'][$e->status] }}">{{ ucfirst($e->status) }}</span></td><td>{{ $e->enrolled_at->format('M j, Y') }}</td></tr>
          @empty
            <tr><td colspan="3" class="text-center text-muted py-3">No enrollments.</td></tr>
          @endforelse
        </tbody></table>
      </div>
    </div>
    <div class="card border-0 shadow-sm">
      <div class="card-header bg-white fw-semibold">Recent Activity</div>
      <div class="table-responsive">
        <table class="table mb-0"><thead class="table-light"><tr><th>Program</th><th>Activity</th><th>Date</th></tr></thead>
        <tbody>
          @forelse($activities as $a)
            <tr><td>{{ $a->program->title }}</td><td>{{ $a->activity_type }} &mdash; {{ $a->description }}</td><td>{{ $a->activity_date->format('M j, Y g:i A') }}</td></tr>
          @empty
            <tr><td colspan="3" class="text-center text-muted py-3">No activity recorded.</td></tr>
          @endforelse
        </tbody></table>
      </div>
    </div>
  </div>
</div>
@endsection
