@extends('layouts.app')
@section('title', 'Modules - ' . $program->title)
@section('content')
<a href="{{ route('admin.modules.picker') }}" class="btn btn-sm btn-outline-secondary mb-3"><i class="fa-solid fa-arrow-left"></i> All Programs</a>
<h3 class="mb-1"><i class="fa-solid fa-book"></i> Modules &mdash; {{ $program->title }}</h3>
<p class="text-muted mb-3">Upload the complete module, then control which topics are visible to trainees based on the current lesson or schedule. Making a module visible notifies every approved trainee in this program.</p>

<div class="row">
  <div class="col-lg-4 mb-3">
    <div class="card border-0 shadow-sm p-3">
      <h6 class="fw-semibold mb-3">Upload New Module</h6>
      <form method="POST" action="{{ route('admin.modules.store') }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="program_id" value="{{ $program->id }}">
        <div class="mb-2"><label class="form-label">Title</label><input class="form-control" name="title" required></div>
        <div class="mb-2"><label class="form-label">Description</label><textarea class="form-control" name="description" rows="2"></textarea></div>
        <div class="mb-2"><label class="form-label">Order</label><input type="number" class="form-control" name="order_num" value="{{ $modules->count()+1 }}"></div>
        <div class="mb-3"><label class="form-label">File (PDF, PPT, DOC, MP4)</label><input type="file" class="form-control" name="module_file" required></div>
        <button class="btn btn-success w-100"><i class="fa-solid fa-upload"></i> Upload</button>
      </form>
    </div>
  </div>
  <div class="col-lg-8">
    <div class="card border-0 shadow-sm">
      <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
          <thead class="table-light"><tr><th>#</th><th>Title</th><th>File</th><th>Visibility</th><th>Actions</th></tr></thead>
          <tbody>
            @forelse($modules as $m)
            <tr>
              <td>{{ $m->order_num }}</td>
              <td>{{ $m->title }}<br><small class="text-muted">{{ $m->description }}</small></td>
              <td>{{ $m->file_path }}</td>
              <td>
                <form method="POST" action="{{ route('admin.modules.toggle', $m) }}" onsubmit="return confirm('{{ $m->is_visible ? 'Hide this module from trainees?' : 'Release this module? All approved trainees will be notified.' }}')">
                  @csrf
                  <button type="submit" class="badge border-0 bg-{{ $m->is_visible?'success':'secondary' }}">
                    <i class="fa-solid fa-{{ $m->is_visible?'eye':'eye-slash' }}"></i> {{ $m->is_visible?'Visible':'Hidden' }}
                  </button>
                </form>
              </td>
              <td>
                <form method="POST" action="{{ route('admin.modules.destroy', $m) }}" onsubmit="return confirm('Delete this module?')">
                  @csrf @method('DELETE')
                  <button class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i></button>
                </form>
              </td>
            </tr>
            @empty
              <tr><td colspan="5" class="text-center text-muted py-3">No modules uploaded yet.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
