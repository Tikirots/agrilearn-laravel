@extends('layouts.app')
@section('title', 'My Profile')
@section('content')
<h3 class="mb-3"><i class="fa-solid fa-id-card"></i> My Profile</h3>

<div class="row">
  <div class="col-md-4">
    <div class="card border-0 shadow-sm p-3 text-center">
      @if($trainee->photo)
        <img src="{{ asset('storage/photos/' . $trainee->photo) }}" alt="Profile photo" class="rounded-circle mx-auto mb-2" style="width:110px;height:110px;object-fit:cover;">
      @else
        <i class="fa-solid fa-circle-user fa-4x text-success mb-2"></i>
      @endif
      <h5>{{ $trainee->full_name }}</h5>
      <p class="text-muted mb-0">{{ $trainee->user->email }}</p>
    </div>
  </div>

  <div class="col-md-8">
    <div class="card border-0 shadow-sm">
      <div class="card-header bg-white fw-semibold">Edit Profile</div>
      <div class="card-body">
        <form method="POST" action="{{ route('trainee.profile.update') }}" enctype="multipart/form-data">
          @csrf
          <div class="mb-3">
            <label class="form-label">Profile Photo</label>
            <input type="file" name="photo" class="form-control" accept="image/jpeg,image/png,image/webp">
            <div class="form-text">JPG, PNG or WEBP. Max 2MB.</div>
          </div>
          <div class="mb-3">
            <label class="form-label">Full Name</label>
            <input type="text" name="full_name" class="form-control" value="{{ old('full_name', $trainee->full_name) }}" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Contact Number</label>
            <input type="text" name="contact_number" class="form-control" value="{{ old('contact_number', $trainee->contact_number) }}" placeholder="e.g. 09171234567">
          </div>
          <div class="mb-3">
            <label class="form-label">Address</label>
            <textarea name="address" class="form-control" rows="2">{{ old('address', $trainee->address) }}</textarea>
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Birthdate</label>
              <input type="date" name="birthdate" class="form-control" value="{{ old('birthdate', $trainee->birthdate?->format('Y-m-d')) }}">
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Gender</label>
              <select name="gender" class="form-select">
                <option value="">-- Select --</option>
                @foreach(['Male', 'Female', 'Other'] as $g)
                  <option value="{{ $g }}" @selected(old('gender', $trainee->gender) === $g)>{{ $g }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <button type="submit" class="btn btn-success">
            <i class="fa-solid fa-floppy-disk"></i> Save Profile
          </button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
