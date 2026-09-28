@extends('layouts.app')
@section('title', 'Register')
@section('content')
<div class="auth-wrap">
  <div class="auth-card" style="max-width:1040px;">
    <div class="row g-0">
      <div class="col-md-4 auth-brand-panel">
        <div>
          <div class="auth-logo-badge">
            <img src="{{ asset('assets/img/agri-icon.png') }}" alt="{{ config('agrilearn.center_name') }} logo">
          </div>
          <h2>Join {{ config('agrilearn.site_name') }}</h2>
          <p>{{ config('agrilearn.center_name') }}</p>
          <ul class="auth-feature-list">
            <li><i class="fa-solid fa-seedling"></i> Free NC II agricultural training</li>
            <li><i class="fa-solid fa-calendar-check"></i> 2–3 month training sets</li>
            <li><i class="fa-solid fa-house-laptop"></i> Learn online, anywhere, anytime</li>
          </ul>
        </div>
        <p class="small mb-0" style="color:rgba(255,255,255,.7);">Already registered? <a href="{{ route('login') }}" style="color:var(--gold);">Log in instead</a></p>
      </div>
      <div class="col-md-8 auth-form-panel">
        <h4 class="mb-1" style="color:var(--earth);">Trainee Registration</h4>
        <p class="text-muted mb-4">Create your account to start enrolling in training programs.</p>
        <div class="alert alert-success small py-2"><i class="fa-solid fa-circle-info"></i> After you submit, your account will be <strong>pending admin approval</strong>. You'll be able to log in once the training center reviews it.</div>
        <form method="POST" action="{{ route('register') }}">
          @csrf
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Full Name *</label>
              <input type="text" name="full_name" class="form-control" value="{{ old('full_name') }}" required>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Username *</label>
              <input type="text" name="username" class="form-control" value="{{ old('username') }}" required>
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label">Email *</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Password *</label>
              <input type="password" name="password" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Confirm Password *</label>
              <input type="password" name="confirm_password" class="form-control" required>
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label">Address</label>
            <input type="text" name="address" class="form-control" value="{{ old('address') }}">
          </div>
          <div class="row">
            <div class="col-md-4 mb-3">
              <label class="form-label">Contact Number</label>
              <input type="text" name="contact_number" class="form-control" value="{{ old('contact_number') }}">
            </div>
            <div class="col-md-4 mb-3">
              <label class="form-label">Birthdate</label>
              <input type="date" name="birthdate" class="form-control" value="{{ old('birthdate') }}">
            </div>
            <div class="col-md-4 mb-3">
              <label class="form-label">Gender</label>
              <select name="gender" class="form-select">
                <option value="">Select</option>
                <option value="Male" @selected(old('gender')=='Male')>Male</option>
                <option value="Female" @selected(old('gender')=='Female')>Female</option>
                <option value="Other" @selected(old('gender')=='Other')>Other</option>
              </select>
            </div>
          </div>
          <button type="submit" class="btn btn-success btn-lg w-100">Register</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
