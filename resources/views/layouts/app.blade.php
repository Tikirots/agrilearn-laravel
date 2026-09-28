<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@hasSection('title')@yield('title') - @endif {{ config('agrilearn.site_name') }}</title>
<link rel="manifest" href="{{ asset('manifest.json') }}">
<link rel="icon" href="{{ asset('assets/img/agri-icon.png') }}">
<meta name="theme-color" content="#2F6B3A">
<link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@400;700;900&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
<link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
</head>
<body>

@auth
@php
    $navUser = auth()->user();
    $isAdminSide = $navUser->isAdminOrTrainer();
    $unreadCount = \App\Services\NotificationService::unreadCount($navUser->id);
    $recentNotifications = \App\Services\NotificationService::recent($navUser->id, 8);
    $displayName = $isAdminSide ? $navUser->name : ($navUser->trainee->full_name ?? $navUser->name);
@endphp
<div class="app-shell">

  <aside class="app-sidebar" id="appSidebar">
    <a class="app-sidebar-brand" href="{{ route($isAdminSide ? 'admin.dashboard' : 'trainee.dashboard') }}">
      <img src="{{ asset('assets/img/agri-icon.png') }}" alt="{{ config('agrilearn.center_name') }} logo">
      <span>{{ config('agrilearn.site_name') }}</span>
    </a>

    <nav class="app-sidebar-nav">
      @if($isAdminSide)
        <a class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-house"></i> Overview</a>
        <a class="{{ request()->routeIs('admin.trainees.*') ? 'active' : '' }}" href="{{ route('admin.trainees.index') }}"><i class="fa-solid fa-user-group"></i> Trainees</a>
        <a class="{{ request()->routeIs('admin.programs.*') ? 'active' : '' }}" href="{{ route('admin.programs.index') }}"><i class="fa-solid fa-layer-group"></i> Programs</a>
        <a class="{{ request()->routeIs('admin.modules.*') ? 'active' : '' }}" href="{{ route('admin.modules.picker') }}"><i class="fa-solid fa-book"></i> Modules</a>
        <a class="{{ request()->routeIs('admin.exams.*') ? 'active' : '' }}" href="{{ route('admin.exams.index') }}"><i class="fa-solid fa-file-pen"></i> Exams</a>
        <a class="{{ request()->routeIs('admin.enrollments.*') ? 'active' : '' }}" href="{{ route('admin.enrollments.index') }}"><i class="fa-solid fa-user-check"></i> Enrollments</a>
        <a class="{{ request()->routeIs('admin.activities.*') ? 'active' : '' }}" href="{{ route('admin.activities.index') }}"><i class="fa-solid fa-clipboard-list"></i> Activities</a>
        <a class="{{ request()->routeIs('admin.certificates.*') ? 'active' : '' }}" href="{{ route('admin.certificates.index') }}"><i class="fa-solid fa-award"></i> Certificates</a>
      @else
        <a class="{{ request()->routeIs('trainee.dashboard') ? 'active' : '' }}" href="{{ route('trainee.dashboard') }}"><i class="fa-solid fa-house"></i> Overview</a>
        <a class="{{ request()->routeIs('trainee.modules.*') ? 'active' : '' }}" href="{{ route('trainee.modules.index') }}"><i class="fa-solid fa-book-open"></i> Learning Modules</a>
        <a class="{{ request()->routeIs('trainee.exams.*') ? 'active' : '' }}" href="{{ route('trainee.exams.index') }}"><i class="fa-solid fa-file-pen"></i> Online Exams</a>
        <a class="{{ request()->routeIs('trainee.my_enrollments.*') || request()->routeIs('trainee.enroll.*') ? 'active' : '' }}" href="{{ route('trainee.my_enrollments.index') }}"><i class="fa-solid fa-calendar-days"></i> Schedule</a>
        <a class="{{ request()->routeIs('trainee.certificates.*') ? 'active' : '' }}" href="{{ route('trainee.certificates.index') }}"><i class="fa-solid fa-award"></i> Certificates</a>
        <a class="{{ request()->routeIs('trainee.profile.*') ? 'active' : '' }}" href="{{ route('trainee.profile.edit') }}"><i class="fa-solid fa-id-card"></i> My Profile</a>
      @endif
    </nav>

    <div class="app-sidebar-foot">
      <div class="app-sidebar-user">
        <span class="app-avatar app-avatar-sm">{{ al_initials($displayName) }}</span>
        <span class="app-sidebar-user-info">
          <strong>{{ $displayName }}</strong>
          <small>{{ ucfirst($navUser->role) }}</small>
        </span>
      </div>
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="app-logout-btn" title="Log out"><i class="fa-solid fa-right-from-bracket"></i></button>
      </form>
    </div>
  </aside>

  <div class="al-overlay" id="alOverlay"></div>

  <div class="app-main">
    <header class="app-topbar">
      <button class="al-burger" id="alBurgerBtn" aria-label="Toggle menu">
        <span></span><span></span><span></span>
      </button>
      <h1 class="app-page-title">@yield('title')</h1>

      <div class="app-topbar-actions">
        <div class="notif-wrap">
          <button class="notif-bell" id="notifBellBtn" aria-label="Notifications">
            <i class="fa-regular fa-bell"></i>
            @if($unreadCount > 0)
              <span class="notif-badge" id="notifBadge">{{ $unreadCount > 9 ? '9+' : $unreadCount }}</span>
            @endif
          </button>
          <div class="notif-dropdown" id="notifDropdown">
            <div class="notif-dropdown-head">
              <span>Notifications</span>
              @if($unreadCount > 0)<button type="button" id="notifMarkAllBtn">Mark all read</button>@endif
            </div>
            <div class="notif-list">
              @forelse($recentNotifications as $n)
                <a class="notif-item {{ $n->is_read ? '' : 'unread' }}" href="{{ $n->link ?: '#' }}">
                  <span class="notif-item-title">{{ $n->title }}</span>
                  <span class="notif-item-msg">{{ $n->message }}</span>
                  <span class="notif-item-time">{{ time_ago($n->created_at) }}</span>
                </a>
              @empty
                <div class="notif-empty">You're all caught up.</div>
              @endforelse
            </div>
            <div class="notif-dropdown-foot">
              <a href="{{ route($isAdminSide ? 'admin.notifications.index' : 'trainee.notifications.index') }}">View all</a>
            </div>
          </div>
        </div>
        <span class="app-avatar" title="{{ $displayName }}">{{ al_initials($displayName) }}</span>
      </div>
    </header>

    <div class="app-content">
@else
<div class="app-shell app-shell-guest">
  <div class="app-main app-main-full">
    <div class="app-content">
@endauth

@if(session('success'))
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
@endif
@if(session('warning'))
  <div class="alert alert-warning alert-dismissible fade show" role="alert">
    {{ session('warning') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
@endif
@if(session('error'))
  <div class="alert alert-danger alert-dismissible fade show" role="alert">
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
@endif
@if($errors->any())
  <div class="alert alert-danger">
    <ul class="mb-0">
      @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
    </ul>
  </div>
@endif

@yield('content')

      <div class="app-footer-note">
        &copy; {{ date('Y') }} {{ config('agrilearn.site_name') }} &mdash; {{ config('agrilearn.center_name') }}
      </div>
    </div><!-- /.app-content -->
  </div><!-- /.app-main -->
</div><!-- /.app-shell -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@auth
<script src="{{ asset('assets/js/script.js') }}"></script>
<script>
  const alBurgerBtn = document.getElementById('alBurgerBtn');
  const appSidebar  = document.getElementById('appSidebar');
  const alOverlay   = document.getElementById('alOverlay');

  function alOpenSidebar()  {
    appSidebar.classList.add('open');
    alOverlay.classList.add('open');
    alBurgerBtn.classList.add('open');
    document.body.classList.add('al-noscroll');
  }
  function alCloseSidebar() {
    appSidebar.classList.remove('open');
    alOverlay.classList.remove('open');
    alBurgerBtn.classList.remove('open');
    document.body.classList.remove('al-noscroll');
  }

  if (alBurgerBtn) {
    alBurgerBtn.addEventListener('click', () => {
      appSidebar.classList.contains('open') ? alCloseSidebar() : alOpenSidebar();
    });
  }
  if (alOverlay) alOverlay.addEventListener('click', alCloseSidebar);

  document.querySelectorAll('.app-sidebar-nav a').forEach(link => {
    link.addEventListener('click', alCloseSidebar);
  });

  const notifBellBtn = document.getElementById('notifBellBtn');
  const notifDropdown = document.getElementById('notifDropdown');
  if (notifBellBtn) {
    notifBellBtn.addEventListener('click', (e) => {
      e.stopPropagation();
      notifDropdown.classList.toggle('open');
    });
    document.addEventListener('click', (e) => {
      if (!notifDropdown.contains(e.target) && e.target !== notifBellBtn) {
        notifDropdown.classList.remove('open');
      }
    });
  }

  const notifMarkAllBtn = document.getElementById('notifMarkAllBtn');
  if (notifMarkAllBtn) {
    notifMarkAllBtn.addEventListener('click', () => {
      fetch('{{ route('ajax.notifications.markAllRead') }}', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
      }).then(() => {
        document.querySelectorAll('.notif-item.unread').forEach(el => el.classList.remove('unread'));
        const badge = document.getElementById('notifBadge');
        if (badge) badge.remove();
        notifMarkAllBtn.remove();
      });
    });
  }
</script>
@endauth
@stack('scripts')
</body>
</html>
