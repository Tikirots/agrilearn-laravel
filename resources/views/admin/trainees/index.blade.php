@extends('layouts.app')
@section('title', 'Trainee Records')
@section('content')
<h3 class="mb-3"><i class="fa-solid fa-users"></i> Trainee Records</h3>
<form class="row g-2 mb-3" method="GET">
  <div class="col-md-4">
    <input type="text" name="q" class="form-control" placeholder="Search by name or email" value="{{ $search }}">
  </div>
  <div class="col-auto"><button class="btn btn-success"><i class="fa-solid fa-search"></i> Search</button></div>
</form>
<div class="card border-0 shadow-sm">
  <div class="table-responsive">
    <table class="table table-hover mb-0 align-middle">
      <thead class="table-light"><tr><th></th><th>Full Name</th><th>Email</th><th>Active Programs</th><th>Account</th><th>Joined</th><th></th></tr></thead>
      <tbody>
        @forelse($trainees as $t)
        <tr>
          <td>
            @if($t->photo)
              <img src="{{ asset('storage/photos/' . $t->photo) }}" alt="" class="rounded-circle" style="width:32px;height:32px;object-fit:cover;">
            @else
              <i class="fa-solid fa-circle-user text-success fa-lg"></i>
            @endif
          </td>
          <td>{{ $t->full_name }}</td>
          <td>{{ $t->user->email }}</td>
          <td>{{ $t->active_programs_count }}</td>
          <td><span class="badge bg-{{ ['pending'=>'warning','active'=>'success','inactive'=>'secondary'][$t->user->status] }}">{{ ucfirst($t->user->status) }}</span></td>
          <td>{{ $t->created_at->format('M j, Y') }}</td>
          <td>
            <a href="{{ route('admin.trainees.show', $t) }}" class="btn btn-sm btn-outline-success"><i class="fa-solid fa-eye"></i> View</a>
            @if($t->user->status === 'pending')
              <form method="POST" action="{{ route('admin.trainees.status', [$t->user, 'approve']) }}" class="d-inline">
                @csrf
                <button class="btn btn-sm btn-success"><i class="fa-solid fa-check"></i></button>
              </form>
              <form method="POST" action="{{ route('admin.trainees.status', [$t->user, 'reject']) }}" class="d-inline" onsubmit="return confirm('Reject this registration?')">
                @csrf
                <button class="btn btn-sm btn-danger"><i class="fa-solid fa-xmark"></i></button>
              </form>
            @endif
          </td>
        </tr>
        @empty
          <tr><td colspan="7" class="text-center text-muted py-3">No trainees found.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
