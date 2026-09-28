@extends('layouts.app')
@section('title', 'My Certificates')
@section('content')
<h3 class="mb-3"><i class="fa-solid fa-certificate"></i> My Certificates</h3>

@if($certificates->isEmpty())
  <p class="text-muted">No certificates issued yet. A certificate becomes available once your program is marked completed by the training center.</p>
@endif

<div class="row g-3">
@foreach($certificates as $c)
  @php $verifyUrl = route('certificate.verify', $c->certificate_code); @endphp
  <div class="col-12">
    <div class="card border-0 shadow-sm p-4 certificate-card">
      <div class="d-flex justify-content-between align-items-center flex-wrap">
        <div>
          <h5 class="mb-1">{{ $trainee->full_name }}</h5>
          <p class="mb-1">has successfully completed</p>
          <h6 class="text-success mb-1">{{ $c->program->title }} ({{ $c->program->nc_level }})</h6>
          <p class="small text-muted mb-0">Certificate Code: <code>{{ $c->certificate_code }}</code> &middot; Issued {{ format_date($c->issued_date) }}</p>
        </div>
        <div class="text-center">
          <img src="{{ qr_code_url($verifyUrl, 130) }}" alt="QR Verification">
          <p class="small text-muted mb-0">Scan to verify</p>
        </div>
      </div>
      <a href="{{ $verifyUrl }}" target="_blank" class="btn btn-success mt-3 align-self-start">
        <i class="fa-solid fa-print"></i> Open Printable Certificate
      </a>
    </div>
  </div>
@endforeach
</div>
@endsection
