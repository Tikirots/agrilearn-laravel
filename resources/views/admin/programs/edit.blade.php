@extends('layouts.app')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
        <h4>Edit Program</h4>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('programs.update', $program->id) }}">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label>Title</label>
                <input type="text" name="title" class="form-control" value="{{ $program->title }}" required>
            </div>
            <div class="mb-3">
                <label>NC Level</label>
                <input type="text" name="nc_level" class="form-control" value="{{ $program->nc_level }}" required>
            </div>
            <div class="mb-3">
                <label>Slots</label>
                <input type="number" name="slots" class="form-control" value="{{ $program->slots }}">
            </div>
            <button type="submit" class="btn btn-warning">Update</button>
        </form>
    </div>
</div>
@endsection
