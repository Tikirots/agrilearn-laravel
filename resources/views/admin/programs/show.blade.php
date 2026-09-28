@extends('layouts.app')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
        <h4>Program Details</h4>
    </div>
    <div class="card-body">
        <h5>{{ $program->title }}</h5>
        <p>NC Level: {{ $program->nc_level }}</p>
        <p>Slots: {{ $program->slots }}</p>
        <p>Status: {{ $program->status }}</p>
        
        <form method="POST" action="{{ route('programs.destroy', $program->id) }}">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Delete Program</button>
        </form>
    </div>
</div>
@endsection
