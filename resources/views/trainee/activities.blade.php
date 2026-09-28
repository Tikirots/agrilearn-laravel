@extends('layouts.app')
@section('title', 'My Activities')
@section('content')
<h3 class="mb-3"><i class="fa-solid fa-chart-line"></i> My Activity Log</h3>
<div class="card border-0 shadow-sm">
  <div class="table-responsive">
    <table class="table mb-0 align-middle">
      <thead class="table-light"><tr><th>Program</th><th>Activity</th><th>Notes</th><th>Date</th></tr></thead>
      <tbody>
        @forelse($activities as $a)
        <tr>
          <td>{{ $a->program->title }}</td>
          <td><span class="badge bg-success-subtle text-success-emphasis">{{ $a->activity_type }}</span></td>
          <td>{{ $a->description }}</td>
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
