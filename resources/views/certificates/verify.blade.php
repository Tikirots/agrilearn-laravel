 
@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 text-center mt-5">
        <div class="card border-success border-3 rounded-lg shadow">
            <div class="card-body py-5">
                <h2 class="text-success fw-bold mb-3">✓ VERIFIED</h2>
                <h4>Certificate of Completion</h4>
                <p class="text-muted">Code: <strong>{{ $certificate->certificate_code }}</strong></p>
                <hr>
                <h3 class="fw-bold">{{ $certificate->trainee->first_name }} {{ $certificate->trainee->last_name }}</h3>
                <p>has successfully completed the program</p>
                <h5 class="text-primary">{{ $certificate->program->title }} ({{ $certificate->program->nc_level }})</h5>
                <p class="text-muted mt-4">Issued on: {{ $certificate->issued_date->format('F d, Y') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
