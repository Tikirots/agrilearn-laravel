@extends('layouts.app')
@section('title', 'Learning Modules')
@section('content')
<h3 class="mb-3"><i class="fa-solid fa-book-open"></i> Learning Modules</h3>

@if($myPrograms->isEmpty())
  <p class="text-muted">You don't have any approved enrollment yet. <a href="{{ route('trainee.enroll.index') }}">Enroll in a program</a> first.</p>
@else
<form method="GET" class="mb-3">
  <select name="program_id" class="form-select w-auto d-inline-block" onchange="this.form.submit()">
    @foreach($myPrograms as $p)
      <option value="{{ $p->id }}" @selected($p->id == $programId)>{{ $p->title }}</option>
    @endforeach
  </select>
</form>

<div class="alert alert-info small"><i class="fa-solid fa-circle-info"></i> To protect the training materials, modules can only be viewed here and cannot be downloaded, copied, or printed.</div>

<div class="row g-3">
  @forelse($modules as $m)
    <div class="col-md-6 col-lg-4">
      <div class="card border-0 shadow-sm h-100 p-3">
        <h6>{{ $m->title }}</h6>
        <p class="small text-muted">{{ $m->description }}</p>
        <a href="{{ route('trainee.modules.show', $m) }}" class="btn btn-sm btn-success mt-auto"><i class="fa-solid fa-eye"></i> View Module</a>
      </div>
    </div>
  @empty
    <div class="col-12"><p class="text-muted text-center py-4">No modules are visible yet for this program. Your trainer will release them according to the training schedule.</p></div>
  @endforelse
</div>
@endif
@endsection
