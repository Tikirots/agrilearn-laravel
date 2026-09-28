@extends('layouts.app')
@section('title', 'Certificates')
@section('content')
<h3 class="mb-3"><i class="fa-solid fa-certificate"></i> Certificate Generation</h3>

<div class="row">
  <div class="col-lg-4 mb-3">
    <div class="card border-0 shadow-sm p-3">
      <h6 class="fw-semibold mb-3">Issue New Certificate</h6>
      <p class="small text-muted">Only trainees marked "Completed" for a program are eligible.</p>
      <form method="POST" action="{{ route('admin.certificates.store') }}">
        @csrf
        <select name="trainee_program" class="form-select mb-3" required onchange="const [t,p]=this.value.split('|');document.getElementById('tid').value=t;document.getElementById('pid').value=p;">
          <option value="">Select completed trainee</option>
          @foreach($completed as $c)
            <option value="{{ $c->trainee_id }}|{{ $c->program_id }}">{{ $c->trainee->full_name }} &mdash; {{ $c->program->title }}</option>
          @endforeach
        </select>
        <input type="hidden" name="trainee_id" id="tid">
        <input type="hidden" name="program_id" id="pid">
        <button class="btn btn-success w-100"><i class="fa-solid fa-certificate"></i> Generate Certificate</button>
      </form>
      @if($completed->isEmpty())<p class="small text-muted mt-2 mb-0">No eligible trainees right now.</p>@endif
    </div>
  </div>
  <div class="col-lg-8">
    <div class="card border-0 shadow-sm">
      <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
          <thead class="table-light"><tr><th>Trainee</th><th>Program</th><th>Code</th><th>Issued</th><th>Actions</th></tr></thead>
          <tbody>
            @forelse($certificates as $c)
            <tr>
              <td>{{ $c->trainee->full_name }}</td>
              <td>{{ $c->program->title }}</td>
              <td><code>{{ $c->certificate_code }}</code></td>
              <td>{{ format_date($c->issued_date) }}</td>
              <td>
                <a href="{{ route('certificate.verify', $c->certificate_code) }}" target="_blank" class="btn btn-sm btn-outline-success"><i class="fa-solid fa-eye"></i> View</a>
              </td>
            </tr>
            @empty
              <tr><td colspan="5" class="text-center text-muted py-3">No certificates issued yet.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
