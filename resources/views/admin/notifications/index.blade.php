@extends('layouts.app')
@section('title', 'Notifications')
@section('content')
<div class="card border-0 shadow-sm">
  <div class="card-header bg-white fw-semibold">All Notifications</div>
  <div class="list-group list-group-flush">
    @forelse($notifications as $n)
      <a href="{{ $n->link ?: '#' }}" class="list-group-item list-group-item-action">
        <div class="d-flex justify-content-between">
          <strong>{{ $n->title }}</strong>
          <small class="text-muted">{{ time_ago($n->created_at) }}</small>
        </div>
        <div class="text-muted small">{{ $n->message }}</div>
      </a>
    @empty
      <div class="list-group-item text-center text-muted py-4">No notifications yet.</div>
    @endforelse
  </div>
</div>
@endsection
