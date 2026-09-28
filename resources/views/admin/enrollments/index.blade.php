@extends('layouts.app')
@section('title', 'Enrollments')
@section('content')
<h3 class="mb-3"><i class="fa-solid fa-user-check"></i> Enrollment Applications</h3>

<div class="btn-group mb-3">
  @foreach(['pending'=>'Pending','approved'=>'Approved','rejected'=>'Rejected','completed'=>'Completed','all'=>'All'] as $k => $label)
    <a href="?status={{ $k }}" class="btn btn-sm btn-outline-success {{ $filter===$k?'active':'' }}">{{ $label }}</a>
  @endforeach
</div>

<div class="card border-0 shadow-sm">
  <div class="table-responsive">
    <table class="table table-hover mb-0 align-middle">
      <thead class="table-light"><tr><th>Trainee</th><th>Contact</th><th>Program</th><th>Applied</th><th>Status</th><th>Actions</th></tr></thead>
      <tbody>
        @forelse($enrollments as $e)
        <tr>
          <td>{{ $e->trainee->full_name }}</td>
          <td>{{ $e->trainee->contact_number }}</td>
          <td>{{ $e->program->title }}</td>
          <td>{{ $e->enrolled_at->format('M j, Y') }}</td>
          <td><span class="badge bg-{{ ['pending'=>'warning','approved'=>'success','rejected'=>'danger','completed'=>'secondary'][$e->status] }}">{{ ucfirst($e->status) }}</span></td>
          <td>
            @if($e->status === 'pending')
              <form method="POST" action="{{ route('admin.enrollments.status', [$e, 'approve']) }}" class="d-inline">
                @csrf<button class="btn btn-sm btn-success"><i class="fa-solid fa-check"></i> Approve</button>
              </form>
              <form method="POST" action="{{ route('admin.enrollments.status', [$e, 'reject']) }}" class="d-inline">
                @csrf<button class="btn btn-sm btn-danger"><i class="fa-solid fa-xmark"></i> Reject</button>
              </form>
            @elseif($e->status === 'approved')
              <form method="POST" action="{{ route('admin.enrollments.status', [$e, 'complete']) }}" class="d-inline" onsubmit="return confirm('Mark this trainee as completed the program?')">
                @csrf<button class="btn btn-sm btn-outline-secondary"><i class="fa-solid fa-flag-checkered"></i> Mark Completed</button>
              </form>
            @endif
          </td>
        </tr>
        @empty
          <tr><td colspan="6" class="text-center text-muted py-3">No records found.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
