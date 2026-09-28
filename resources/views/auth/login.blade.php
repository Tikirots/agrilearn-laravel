@extends('layouts.app')
@section('title', 'Login')
@section('content')
<div class="auth-wrap">
  <div class="auth-card">
    <div class="row g-0">
      <div class="col-md-5 auth-brand-panel">
        <div>
          <div class="auth-logo-badge">
            <img src="{{ asset('assets/img/agri-icon.png') }}" alt="{{ config('agrilearn.center_name') }} logo">
          </div>
          <h2>{{ config('agrilearn.site_name') }}</h2>
          <p>{{ config('agrilearn.center_name') }}</p>
          <ul class="auth-feature-list">
            <li><i class="fa-solid fa-clipboard-check"></i> Enroll and track your NC II training</li>
            <li><i class="fa-solid fa-book-open"></i> Access modules released by your trainer</li>
            <li><i class="fa-solid fa-certificate"></i> Get certificates with QR verification</li>
          </ul>
        </div>
        <p class="small mb-0" style="color:rgba(255,255,255,.7);">Growing skills, harvesting opportunities.</p>
      </div>
      <div class="col-md-7 auth-form-panel">
        <h4 class="mb-1" style="color:var(--earth);">Welcome back</h4>
        <p class="text-muted mb-4">Log in to continue your training.</p>
        <form method="POST" action="{{ route('login') }}">
          @csrf
          <div class="mb-3">
            <label class="form-label">Username or Email</label>
            <input type="text" name="username" class="form-control form-control-lg" value="{{ old('username') }}" required autofocus>
          </div>
          <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control form-control-lg" required>
          </div>
          <button type="submit" class="btn btn-success btn-lg w-100">Login</button>
        </form>
        <p class="text-center mt-4 mb-0 small">New trainee? <a href="{{ route('register') }}">Register here</a></p>
      </div>
    </div>
  </div>
</div>
@endsection
