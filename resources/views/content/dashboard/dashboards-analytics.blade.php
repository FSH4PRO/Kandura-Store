@extends('layouts.contentNavbarLayout')

@section('title', __('dashboard.title'))

@section('vendor-style')
  @vite('resources/assets/vendor/libs/apex-charts/apex-charts.scss')
@endsection

@section('vendor-script')
  @vite('resources/assets/vendor/libs/apex-charts/apexcharts.js')
@endsection

@section('page-script')
  @vite('resources/assets/js/dashboards-analytics.js')
@endsection

@php
  
  $admin = auth('admin')->user();

  $currentUser = $admin?->user;

  $locale = app()->getLocale();

 
  $displayName = 'مشرف';
  if ($currentUser) {
      $rawName = $currentUser->name;
      if (is_array($rawName)) {
          $displayName = $rawName[$locale] ?? ($rawName['en'] ?? $displayName);
      } elseif (!empty($rawName)) {
          $displayName = $rawName;
      }
  }

  $adminEmail = $admin?->email ?? '-';

  
  $totalUsers = $totalUsers ?? 0;
  $totalActiveUsers = $totalActiveUsers ?? 0;
  $totalAdmins = $totalAdmins ?? 0;
  $totalAddresses = $totalAddresses ?? 0;

  $totalCustomers = $totalCustomers ?? 0;
  $totalDesigns = $totalDesigns ?? 0;
  $totalDesignsToday = $totalDesignsToday ?? 0;
  $totalDesignOptions = $totalDesignOptions ?? 0;
@endphp

@section('content')
  <div class="row">
  
    <div class="col-12 mb-6">
      <div class="card">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-4">
          <div class="card-body">
            <h5 class="card-title text-primary mb-2">
              {{ __('dashboard.hero.hello', ['name' => $displayName]) }}
            </h5>
            <p class="mb-3">
              {!! __('dashboard.hero.subtitle', ['app' => 'Kandura Store']) !!}
            </p>

            <div class="d-flex flex-wrap gap-4">
              <div>
                <small class="text-muted d-block mb-1">{{ __('dashboard.stats.total_users') }}</small>
                <h4 class="mb-0">{{ $totalUsers }}</h4>
              </div>
              <div>
                <small class="text-muted d-block mb-1">{{ __('dashboard.stats.active_users') }}</small>
                <h4 class="mb-0">{{ $totalActiveUsers }}</h4>
              </div>
              <div>
                <small class="text-muted d-block mb-1">{{ __('dashboard.stats.total_admins') }}</small>
                <h4 class="mb-0">{{ $totalAdmins }}</h4>
              </div>
            </div>
          </div>
          <div class="card-body text-center">
            <img src="{{ asset('assets/img/illustrations/man-with-laptop.png') }}" height="140"
              alt="Kandura Store Admin" />
          </div>
        </div>
      </div>
    </div>
  </div>

  
  <div class="row">
    {{-- Users & Addresses --}}
    <div class="col-12 col-xl-8 mb-6">
      <div class="row">
        <div class="col-md-6 mb-6">
          <div class="card h-100">
            <div class="card-body">
              <div class="d-flex align-items-start justify-content-between mb-4">
                <div class="avatar flex-shrink-0">
                  <span class="avatar-initial rounded bg-label-primary">
                    <i class="bx bx-user icon-lg text-primary"></i>
                  </span>
                </div>
              </div>
              <p class="mb-1">{{ __('dashboard.stats.total_users') }}</p>
              <h4 class="card-title mb-2">{{ $totalUsers }}</h4>
              <small class="text-muted">{{ __('dashboard.stats.total_users_help') }}</small>
            </div>
          </div>
        </div>

        <div class="col-md-6 mb-6">
          <div class="card h-100">
            <div class="card-body">
              <div class="d-flex align-items-start justify-content-between mb-4">
                <div class="avatar flex-shrink-0">
                  <span class="avatar-initial rounded bg-label-success">
                    <i class="bx bx-home-alt icon-lg text-success"></i>
                  </span>
                </div>
              </div>
              <p class="mb-1">{{ __('dashboard.stats.total_addresses') }}</p>
              <h4 class="card-title mb-2">{{ $totalAddresses }}</h4>
              <small class="text-muted">{{ __('dashboard.stats.total_addresses_help') }}</small>
            </div>
          </div>
        </div>
      </div>

      {{-- Extra stats: Customers / Designs --}}
      <div class="row">
        <div class="col-md-4 mb-6">
          <div class="card h-100">
            <div class="card-body">
              <div class="d-flex align-items-start justify-content-between mb-4">
                <div class="avatar flex-shrink-0">
                  <span class="avatar-initial rounded bg-label-info">
                    <i class="bx bx-user-circle icon-lg"></i>
                  </span>
                </div>
              </div>
              <p class="mb-1">{{ __('dashboard.stats.total_customers') }}</p>
              <h4 class="card-title mb-2">{{ $totalCustomers }}</h4>
              <small class="text-muted">{{ __('dashboard.stats.total_customers_help') }}</small>
            </div>
          </div>
        </div>

        <div class="col-md-4 mb-6">
          <div class="card h-100">
            <div class="card-body">
              <div class="d-flex align-items-start justify-content-between mb-4">
                <div class="avatar flex-shrink-0">
                  <span class="avatar-initial rounded bg-label-warning">
                    <i class="bx bx-layer icon-lg"></i>
                  </span>
                </div>
              </div>
              <p class="mb-1">{{ __('dashboard.stats.total_designs') }}</p>
              <h4 class="card-title mb-2">{{ $totalDesigns }}</h4>
              <small class="text-muted">{{ __('dashboard.stats.total_designs_help') }}</small>
            </div>
          </div>
        </div>

        <div class="col-md-4 mb-6">
          <div class="card h-100">
            <div class="card-body">
              <div class="d-flex align-items-start justify-content-between mb-4">
                <div class="avatar flex-shrink-0">
                  <span class="avatar-initial rounded bg-label-danger">
                    <i class="bx bx-trending-up icon-lg"></i>
                  </span>
                </div>
              </div>
              <p class="mb-1">{{ __('dashboard.stats.designs_today') }}</p>
              <h4 class="card-title mb-2">{{ $totalDesignsToday }}</h4>
              <small class="text-muted">{{ __('dashboard.stats.designs_today_help') }}</small>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- /**** Simple Chart Placeholder ****/ --}}
    <div class="col-12 col-xl-4 mb-6">
      <div class="card h-100">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="mb-0">{{ __('dashboard.charts.users_growth_title') }}</h5>
          <small class="text-muted">{{ __('dashboard.charts.users_growth_subtitle') }}</small>
        </div>
        <div class="card-body">
          {{-- JS يرسم عليها مخطط نمو المستخدمين --}}
          <div id="usersGrowthChart"></div>
        </div>
      </div>
    </div>
  </div>

  {{-- /**** System Info & Quick Stats ****/ --}}
  <div class="row">
    <div class="col-lg-4 mb-6">
      <div class="card h-100">
        <div class="card-header">
          <h5 class="mb-0">{{ __('dashboard.system_info.title') }}</h5>
        </div>
        <div class="card-body">
          <ul class="list-unstyled mb-0">
            <li class="mb-3 d-flex justify-content-between align-items-center">
              <span>{{ __('dashboard.system_info.laravel') }}</span>
              <span class="fw-medium">{{ app()->version() }}</span>
            </li>

            <li class="mb-3 d-flex justify-content-between align-items-center">
              <span>{{ __('dashboard.system_info.locale') }}</span>
              <span class="fw-medium">{{ app()->getLocale() }}</span>
            </li>

            <li class="mb-3 d-flex justify-content-between align-items-center">
              <span>{{ __('dashboard.system_info.current_user') }}</span>
              <span class="fw-medium">{{ $adminEmail }}</span>
            </li>

            <li class="mb-3 d-flex justify-content-between align-items-center">
              <span>{{ __('dashboard.system_info.total_admins') }}</span>
              <span class="fw-medium">{{ $totalAdmins }}</span>
            </li>

            <li class="mb-3 d-flex justify-content-between align-items-center">
              <span>{{ __('dashboard.system_info.total_design_options') }}</span>
              <span class="fw-medium">{{ $totalDesignOptions }}</span>
            </li>

            <li class="mb-1 d-flex justify-content-between align-items-center">
              <span>{{ __('dashboard.system_info.today') }}</span>
              <span class="fw-medium">{{ now()->format('Y-m-d') }}</span>
            </li>
          </ul>
        </div>
      </div>
    </div>

    {{-- ممكن تضيف مثلاً آخر التصاميم / آخر المستخدمين لاحقاً --}}
  </div>
@endsection
