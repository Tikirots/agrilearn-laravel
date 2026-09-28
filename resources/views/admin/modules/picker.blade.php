@extends('layouts.app')
@section('title', 'Modules')
@section('content')
<h3 class="mb-1"><i class="fa-solid fa-book"></i> Modules</h3>
<p class="text-muted mb-3">Choose a training program to manage its modules.</p>

<div class="card border-0 shadow-sm">
  <div class="table-responsive">
    <table class="table table-hover mb-0 align-middle">
      <thead class="table-light"><tr><th>Program</th><th>NC Level</th><th>Modules</th><th>Status</th><th></th></tr></thead>
      <tbody>
        @forelse($programs as $p)
        <tr>
          <td class="fw-semibold">{{ $p->title }}</td>
          <td>{{ $p->nc_level }}</td>
          <td>{{ $p->modules_count }}</td>
          <td><span class="badge bg-{{ $p->status==='open'?'success':($p->status==='ongoing'?'warning':'secondary') }}">{{ ucfirst($p->status) }}</span></td>
          <td>
            <a href="{{ route('admin.modules.manage', ['program_id' => $p->id]) }}" class="btn btn-sm btn-success">
              <i class="fa-solid fa-book"></i> Manage Modules
            </a>
          </td>
        </tr>
        @empty
          <tr><td colspan="5" class="text-center text-muted py-3">No training programs yet. <a href="{{ route('admin.programs.index') }}">Create one first</a>.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
