@extends('layouts.app')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
        <h4>Create Program</h4>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('programs.store') }}">
            @csrf
            <div class="mb-3">
                <label>Title</label>
                <input type="text" name="title" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>NC Level</label>
                <input type="text" name="nc_level" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Slots</label>
                <input type="number" name="slots" class="form-control" value="0">
            </div>
            <button type="submit" class="btn btn-success">Create</button>
        </form>
    </div>
</div>
@endsection
