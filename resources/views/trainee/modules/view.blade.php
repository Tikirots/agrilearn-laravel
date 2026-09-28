 
@extends('layouts.app')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
        <h4>{{ $module->title }}</h4>
    </div>
    <div class="card-body text-center">
        <!-- Assuming $module->file_path holds the path to a video/PDF -->
        @if(Str::endsWith($module->file_path, ['.mp4', '.webm']))
            <video width="100%" controls>
                <source src="{{ asset('storage/' . $module->file_path) }}" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        @else
            <iframe src="{{ asset('storage/' . $module->file_path) }}" width="100%" height="600px"></iframe>
        @endif
        
        <p class="mt-3 text-muted">{{ $module->description }}</p>
    </div>
</div>
@endsection
