@extends('layouts.app')
@section('title', 'Training Programs')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h3><i class="fa-solid fa-layer-group"></i> Training Programs</h3>
  <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#programModal" onclick="resetForm()">
    <i class="fa-solid fa-plus"></i> New Program
  </button>
</div>

<div class="card border-0 shadow-sm">
  <div class="table-responsive">
    <table class="table table-hover mb-0 align-middle">
      <thead class="table-light"><tr><th>Title</th><th>NC Level</th><th>Schedule</th><th>Slots</th><th>Enrolled</th><th>Status</th><th>Actions</th></tr></thead>
      <tbody>
        @forelse($programs as $p)
        <tr>
          <td class="fw-semibold">{{ $p->title }}</td>
          <td>{{ $p->nc_level }}</td>
          <td>{{ format_date($p->start_date) }} &ndash; {{ format_date($p->end_date) }}</td>
          <td>{{ $p->slots }}</td>
          <td>{{ $p->enrolled_count }}</td>
          <td><span class="badge bg-{{ $p->status==='open'?'success':($p->status==='ongoing'?'warning':'secondary') }}">{{ ucfirst($p->status) }}</span></td>
          <td>
            <a href="{{ route('admin.modules.manage', ['program_id' => $p->id]) }}" class="btn btn-sm btn-outline-success" title="Modules"><i class="fa-solid fa-book"></i></a>
            <button class="btn btn-sm btn-outline-primary" title="Edit" onclick='editProgram(@json($p))'><i class="fa-solid fa-pen"></i></button>
            <form method="POST" action="{{ route('admin.programs.destroy', $p) }}" class="d-inline" onsubmit="return confirm('Delete this program? This also removes its modules and enrollments.')">
              @csrf @method('DELETE')
              <button class="btn btn-sm btn-outline-danger" title="Delete"><i class="fa-solid fa-trash"></i></button>
            </form>
          </td>
        </tr>
        @empty
          <tr><td colspan="7" class="text-center text-muted py-3">No training programs yet.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

<div class="modal fade" id="programModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" action="{{ route('admin.programs.store') }}">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="modalTitle">New Training Program</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id" id="f_id">
          <div class="mb-2"><label class="form-label">Title</label><input class="form-control" name="title" id="f_title" required></div>
          <div class="mb-2"><label class="form-label">NC Level</label><input class="form-control" name="nc_level" id="f_nc" value="NC II" required></div>
          <div class="mb-2"><label class="form-label">Description</label><textarea class="form-control" name="description" id="f_desc" rows="2"></textarea></div>
          <div class="row">
            <div class="col-6 mb-2"><label class="form-label">Start Date</label><input type="date" class="form-control" name="start_date" id="f_start"></div>
            <div class="col-6 mb-2"><label class="form-label">End Date</label><input type="date" class="form-control" name="end_date" id="f_end"></div>
          </div>
          <div class="row">
            <div class="col-6 mb-2"><label class="form-label">Slots</label><input type="number" class="form-control" name="slots" id="f_slots" value="30" required></div>
            <div class="col-6 mb-2"><label class="form-label">Status</label>
              <select class="form-select" name="status" id="f_status">
                <option value="open">Open</option>
                <option value="ongoing">Ongoing</option>
                <option value="closed">Closed</option>
              </select>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
function resetForm() {
  document.getElementById('modalTitle').innerText = 'New Training Program';
  document.getElementById('f_id').value = '';
  document.getElementById('f_title').value = '';
  document.getElementById('f_nc').value = 'NC II';
  document.getElementById('f_desc').value = '';
  document.getElementById('f_start').value = '';
  document.getElementById('f_end').value = '';
  document.getElementById('f_slots').value = 30;
  document.getElementById('f_status').value = 'open';
}
function editProgram(p) {
  document.getElementById('modalTitle').innerText = 'Edit Training Program';
  document.getElementById('f_id').value = p.id;
  document.getElementById('f_title').value = p.title;
  document.getElementById('f_nc').value = p.nc_level;
  document.getElementById('f_desc').value = p.description || '';
  document.getElementById('f_start').value = p.start_date || '';
  document.getElementById('f_end').value = p.end_date || '';
  document.getElementById('f_slots').value = p.slots;
  document.getElementById('f_status').value = p.status;
  new bootstrap.Modal(document.getElementById('programModal')).show();
}
</script>
@endsection
