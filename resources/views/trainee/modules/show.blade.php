@extends('layouts.app')
@section('title', $module->title)
@section('content')
<a href="{{ route('trainee.modules.index', ['program_id' => $module->program_id]) }}" class="btn btn-sm btn-outline-secondary mb-3"><i class="fa-solid fa-arrow-left"></i> Back to Modules</a>
<h4>{{ $module->title }}</h4>
<p class="text-muted small">{{ $module->program->title }}</p>

<div class="module-viewer card border-0 shadow-sm p-2" oncontextmenu="return false;">
  @if($ext === 'pdf')
    <iframe src="{{ route('trainee.modules.stream', $module) }}#toolbar=0" class="w-100" style="height:80vh;border:0;"></iframe>
  @elseif($ext === 'mp4')
    <video src="{{ route('trainee.modules.stream', $module) }}" class="w-100" controls controlsList="nodownload noremoteplayback" style="max-height:80vh;" oncontextmenu="return false;"></video>
  @else
    <div class="text-center py-5">
      <i class="fa-solid fa-file-lines fa-3x text-success mb-3"></i>
      <p>This file type ({{ $ext }}) can only be previewed within the system for security. Please ask your trainer if you need an alternate viewing option.</p>
      <iframe src="https://docs.google.com/gview?url={{ urlencode(route('trainee.modules.stream', $module)) }}&embedded=true" class="w-100" style="height:75vh;border:0;"></iframe>
    </div>
  @endif
</div>
<p class="small text-muted mt-2"><i class="fa-solid fa-lock"></i> Downloading, copying, and screenshotting are disabled where supported by your device.</p>
@endsection
