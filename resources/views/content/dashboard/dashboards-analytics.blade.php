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

@section('content')
  <div class="row">
    {{-- /****************** Overview Hero ******************/ --}}
    <div class="col-12 mb-6">
      <div class="card">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-4">
          <div class="card-body">
            <h5 class="card-title text-primary mb-2">
              {{ __('dashboard.hero.hello', [
                  'name' => is_array(auth()->user()->name)
                      ? auth()->user()->name['ar'] ?? (auth()->user()->name['en'] ?? 'مشرف')
                      : auth()->user()->name,
              ]) }}
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

    {{-- /****************** Top Stats Cards ******************/ --}}
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
              <small class="text-muted">{{ __('dashboard.stats.total_users') }}</small>
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
              <small class="text-muted">{{ __('dashboard.stats.total_addresses') }}</small>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- /****************** Simple Chart Placeholder ******************/ --}}
    <div class="col-12 col-xl-4 mb-6">
      <div class="card h-100">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="mb-0">{{ __('dashboard.charts.users_growth_title') }}</h5>
          <small class="text-muted">{{ __('dashboard.charts.users_growth_subtitle') }}</small>
        </div>
        <div class="card-body">
          {{-- استخدم هذا الـ div في JS لرسم مخطط بسيط لعدد المستخدمين --}}
          <div id="usersGrowthChart"></div>
        </div>
      </div>
    </div>
  </div>
  {{-- Quick System Info --}}
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
          @php
            $currentOwner = auth()->user()->usable;
          @endphp

          <li class="mb-3 d-flex justify-content-between align-items-center">
            <span>{{ __('dashboard.system_info.current_user') }}</span>
            <span class="fw-medium">{{ $currentOwner->email ?? '-' }}</span>
          </li>

          <li class="mb-1 d-flex justify-content-between align-items-center">
            <span>{{ __('dashboard.system_info.today') }}</span>
            <span class="fw-medium">{{ now()->format('Y-m-d') }}</span>
          </li>
        </ul>
      </div>
    </div>
  </div>
  </div>
@endsection
