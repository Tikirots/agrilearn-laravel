@extends('layouts.app')
@section('title', 'Enroll')
@section('content')
<h3 class="mb-3"><i class="fa-solid fa-pen-to-square"></i> Available Training Programs</h3>
<div class="row g-3">
  @forelse($programs as $p)
    <div class="col-md-6 col-lg-4">
      <div class="card border-0 shadow-sm h-100 p-3">
        <span class="badge bg-{{ $p->status==='open'?'success':'warning' }} mb-2 align-self-start">{{ ucfirst($p->status) }}</span>
        <h5>{{ $p->title }}</h5>
        <p class="text-muted small mb-1">{{ $p->nc_level }}</p>
        <p class="small">{{ $p->description }}</p>
        <p class="small mb-1"><i class="fa-regular fa-calendar"></i> {{ format_date($p->start_date) }} &ndash; {{ format_date($p->end_date) }}</p>
        <p class="small text-muted mb-3"><i class="fa-solid fa-users"></i> {{ $p->enrolled_count }} / {{ $p->slots }} slots filled</p>
        <form method="POST" action="{{ route('trainee.enroll.store') }}" class="mt-auto">
          @csrf
          <input type="hidden" name="program_id" value="{{ $p->id }}">
          <button class="btn btn-success w-100" @if($p->enrolled_count >= $p->slots) disabled @endif>
            {{ $p->enrolled_count >= $p->slots ? 'Fully Booked' : 'Apply / Enroll' }}
          </button>
        </form>
      </div>
    </div>
  @empty
    <div class="col-12"><p class="text-muted text-center py-4">No open programs available right now, or you've already applied to all of them.</p></div>
  @endforelse
</div>
@endsection
