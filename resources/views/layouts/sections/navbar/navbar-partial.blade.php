@php
  use Illuminate\Support\Facades\Auth;

  // Admin من جدول admins
  /** @var \App\Models\Admin|null $admin */
  $admin = Auth::guard('admin')->user();

  // User المرتبط (morphOne) عليه الاسم و الصورة الخ...
  /** @var \App\Models\User|null $currentUser */
  $currentUser = $admin?->user;

  $locale = app()->getLocale();

  // الاسم المعروض
  $displayName = config('variables.templateName');

  if ($currentUser) {
      $rawName = $currentUser->name;

      if (is_array($rawName)) {
          $displayName = $rawName[$locale] ?? ($rawName['en'] ?? reset($rawName));
      } else {
          $displayName = $rawName;
      }
  }

  // الرول الأساسي (من موديل Admin نفسه لأن عليه HasRoles)
  $primaryRole = null;
  if ($admin && method_exists($admin, 'getRoleNames')) {
      $primaryRole = $admin->getRoleNames()->first();
  }

  // الإيميل من جدول admins
  $adminEmail = $admin?->email;

  // الصورة من MediaLibrary على موديل User (profile_image collection)
  $avatarUrl = $currentUser?->avatar_url ?? asset('assets/img/avatars/1.png');

  // اللغة التالية اللي رح نبدّل إلها
  $switchTo = $locale === 'ar' ? 'en' : 'ar';
@endphp

{{-- Brand (يظهر فقط في navbar-full وفوق xl) --}}
@if (isset($navbarFull))
  <div class="navbar-brand app-brand demo d-none d-xl-flex py-0 me-4">
    <a href="{{ url('/') }}" class="app-brand-link gap-2">
      <span class="app-brand-logo demo">@include('_partials.macros')</span>
      <span class="app-brand-text demo menu-text fw-bold text-heading">
        {{ $displayName }}
      </span>
    </a>
  </div>
@endif

{{-- زر إظهار/إخفاء المينيو في الشاشات الصغيرة --}}
@if (!isset($navbarHideToggle))
  <div
    class="layout-menu-toggle navbar-nav align-items-xl-center me-4 me-xl-0 {{ isset($contentNavbar) ? ' d-xl-none ' : '' }}">
    <a class="nav-item nav-link px-0 me-xl-6" href="javascript:void(0)">
      <i class="icon-base bx bx-menu icon-md"></i>
    </a>
  </div>
@endif

<div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
  {{-- Search --}}
  <div class="navbar-nav align-items-center">
    <div class="nav-item d-flex align-items-center">
      <i class="icon-base bx bx-search icon-md"></i>
      <input type="text" class="form-control border-0 shadow-none ps-1 ps-sm-2"
        placeholder="{{ __('navbar.search_placeholder') }}" aria-label="{{ __('navbar.search_placeholder') }}">
    </div>
  </div>
  {{-- /Search --}}

  <ul class="navbar-nav flex-row align-items-center ms-auto">
    {{-- User dropdown --}}
    <li class="nav-item navbar-dropdown dropdown-user dropdown">
      <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);" data-bs-toggle="dropdown">
        <div class="avatar avatar-online">
          <img src="{{ $avatarUrl }}" alt="avatar" class="w-px-40 h-auto rounded-circle">
        </div>
      </a>
      <ul class="dropdown-menu dropdown-menu-end">
        <li>
          <a class="dropdown-item" href="javascript:void(0);">
            <div class="d-flex">
              <div class="flex-shrink-0 me-3">
                <div class="avatar avatar-online">
                  <img src="{{ $avatarUrl }}" alt="avatar" class="w-px-40 h-auto rounded-circle">
                </div>
              </div>
              <div class="flex-grow-1">
                <h6 class="mb-0">{{ $displayName }}</h6>
                <small class="text-muted d-block">
                  @if ($primaryRole)
                    {{ $primaryRole }}
                  @else
                    {{ __('navbar.default_role') }}
                  @endif
                </small>
                @if ($adminEmail)
                  <div class="text-muted small">{{ $adminEmail }}</div>
                @endif
              </div>
            </div>
          </a>
        </li>

        <li>
          <div class="dropdown-divider my-1"></div>
        </li>

        {{-- Settings (اختياري) --}}
        <li>
          <a class="dropdown-item" href="javascript:void(0);">
            <i class="icon-base bx bx-cog icon-md me-3"></i>
            <span>{{ __('navbar.settings') }}</span>
          </a>
        </li>

        {{-- Billing (اختياري) --}}
        <li>
          <a class="dropdown-item" href="javascript:void(0);">
            <span class="d-flex align-items-center align-middle">
              <i class="flex-shrink-0 icon-base bx bx-credit-card icon-md me-3"></i>
              <span class="flex-grow-1 align-middle">
                {{ __('navbar.billing') }}
              </span>
              <span class="flex-shrink-0 badge rounded-pill bg-danger">4</span>
            </span>
          </a>
        </li>

        <li>
          <div class="dropdown-divider my-1"></div>
        </li>

        {{-- Logout --}}
        <li>
          <form method="POST" action="{{ route('admin.logout') }}" style="display: inline;">
            @csrf
            <button type="submit" class="dropdown-item"
              style="border: none; background: none; cursor: pointer; width: 100%; text-align: left;">
              <i class="icon-base bx bx-power-off icon-md me-3"></i>
              <span>{{ __('navbar.logout') }}</span>
            </button>
          </form>
        </li>
      </ul>
    </li>

    {{-- Language switch --}}
    <li class="nav-item">
      <a class="nav-link" href="{{ route('switch.lang') }}">{{ ($locale === 'ar') ? 'EN' : 'AR' }}</a>
    </li>
  </ul>
</div>
