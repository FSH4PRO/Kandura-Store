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

  $displayName = __('dashboard.hero.default_admin_name');
  if ($currentUser) {
      $rawName = $currentUser->name;
      if (is_array($rawName)) {
          $displayName = $rawName[$locale] ?? ($rawName['en'] ?? $displayName);
      } elseif (!empty($rawName)) {
          $displayName = $rawName;
      }
  }
@endphp

@section('content')
  <!-- Welcome Section -->
  <div class="row mb-6">
    <div class="col-12">
      <div class="card bg-primary text-white">
        <div class="card-body">
          <div class="row align-items-center">
            <div class="col-md-8">
              <h4 class="card-title text-white mb-2">
                {{ __('dashboard.hero.hello', ['name' => $displayName]) }}
              </h4>
              <p class="mb-0 opacity-75">
                {{ __('dashboard.hero.subtitle') }}
              </p>
            </div>
            <div class="col-md-4 text-center">
              <img src="{{ asset('assets/img/illustrations/man-with-laptop.png') }}" height="120" alt="Dashboard"
                class="img-fluid">
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Key Metrics Cards -->
  <div class="row mb-6">
    <!-- Orders Metrics -->
    <div class="col-xl-3 col-md-6 col-12 mb-4">
      <div class="card h-100">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-start">
            <div class="avatar flex-shrink-0">
              <div class="avatar-initial bg-label-success rounded">
                <i class="bx bx-shopping-bag bx-sm"></i>
              </div>
            </div>
            <div class="dropdown">
              <button class="btn p-0" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown">
                <i class="bx bx-dots-vertical-rounded"></i>
              </button>
              <div class="dropdown-menu dropdown-menu-end">
                <a class="dropdown-item"
                  href="{{ route('admin.orders.index') }}">{{ __('dashboard.actions.view_all') }}</a>
              </div>
            </div>
          </div>
          <div class="card-info mt-3">
            <h6 class="mb-1">{{ __('dashboard.metrics.total_orders') }}</h6>
            <h4 class="mb-0">{{ number_format($totalOrders) }}</h4>
            <small class="text-success fw-medium">
              <i class="bx bx-up-arrow-alt"></i> {{ __('dashboard.metrics.this_month', ['count' => $ordersThisMonth]) }}
            </small>
          </div>
        </div>
      </div>
    </div>

    <!-- Completed Orders -->
    <div class="col-xl-3 col-md-6 col-12 mb-4">
      <div class="card h-100">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-start">
            <div class="avatar flex-shrink-0">
              <div class="avatar-initial bg-label-info rounded">
                <i class="bx bx-check-circle bx-sm"></i>
              </div>
            </div>
          </div>
          <div class="card-info mt-3">
            <h6 class="mb-1">{{ __('dashboard.metrics.completed_orders') }}</h6>
            <h4 class="mb-0">{{ number_format($completedOrders) }}</h4>
            <small class="text-info fw-medium">
              {{ $totalOrders > 0 ? round(($completedOrders / $totalOrders) * 100, 1) : 0 }}%
              {{ __('dashboard.metrics.of_total') }}
            </small>
          </div>
        </div>
      </div>
    </div>

    <!-- Users -->
    <div class="col-xl-3 col-md-6 col-12 mb-4">
      <div class="card h-100">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-start">
            <div class="avatar flex-shrink-0">
              <div class="avatar-initial bg-label-primary rounded">
                <i class="bx bx-user bx-sm"></i>
              </div>
            </div>
            <div class="dropdown">
              <button class="btn p-0" type="button" id="dropdownMenuButton2" data-bs-toggle="dropdown">
                <i class="bx bx-dots-vertical-rounded"></i>
              </button>
              <div class="dropdown-menu dropdown-menu-end">
                <a class="dropdown-item"
                  href="{{ route('users.index') }}">{{ __('dashboard.actions.view_all') }}</a>
              </div>
            </div>
          </div>
          <div class="card-info mt-3">
            <h6 class="mb-1">{{ __('dashboard.metrics.total_users') }}</h6>
            <h4 class="mb-0">{{ number_format($totalUsers) }}</h4>
            <small class="text-primary fw-medium">
              {{ $totalActiveUsers }} {{ __('dashboard.metrics.active') }}
            </small>
          </div>
        </div>
      </div>
    </div>

    <!-- Wallets -->
    <div class="col-xl-3 col-md-6 col-12 mb-4">
      <div class="card h-100">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-start">
            <div class="avatar flex-shrink-0">
              <div class="avatar-initial bg-label-warning rounded">
                <i class="bx bx-wallet bx-sm"></i>
              </div>
            </div>
            <div class="dropdown">
              <button class="btn p-0" type="button" id="dropdownMenuButton3" data-bs-toggle="dropdown">
                <i class="bx bx-dots-vertical-rounded"></i>
              </button>
              <div class="dropdown-menu dropdown-menu-end">
                <a class="dropdown-item"
                  href="{{ route('admin.wallets.index') }}">{{ __('dashboard.actions.view_all') }}</a>
                <a class="dropdown-item"
                  href="{{ route('dashboard.transactions.index') }}">{{ __('dashboard.actions.view_transactions') }}</a>
              </div>
            </div>
          </div>
          <div class="card-info mt-3">
            <h6 class="mb-1">{{ __('dashboard.metrics.total_wallet_balance') }}</h6>
            <h4 class="mb-0">{{ number_format($totalWalletBalance, 2) }} AED</h4>
            <small class="text-warning fw-medium">
              {{ $activeWallets }} {{ __('dashboard.metrics.active_wallets') }}
            </small>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Bottom Row -->
  <div class="row">
    <!-- Recent Transactions -->
    <div class="col-xl-6 mb-4">
      <div class="card h-100">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="card-title mb-0">{{ __('dashboard.recent_transactions.title') }}</h5>
          <a href="{{ route('admin.wallets.index') }}" class="btn btn-sm btn-outline-primary">
            {{ __('dashboard.actions.view_all') }}
          </a>
        </div>
        <div class="card-body pb-0">
          @if ($recentTransactions->count() > 0)
            @foreach ($recentTransactions as $transaction)
              <div class="d-flex align-items-center mb-3">
                @if ($transaction->wallet->customer->user->getFirstMediaUrl('profile'))
                  <img src="{{ $transaction->wallet->customer->user->getFirstMediaUrl('profile') }}" alt="Avatar"
                    class="rounded-circle me-3" width="40" height="40">
                @else
                  <div class="avatar avatar-sm me-3">
                    <span
                      class="avatar-initial rounded-circle bg-label-primary">{{ substr($transaction->wallet->customer->user->name, 0, 1) }}</span>
                  </div>
                @endif
                <div class="flex-grow-1">
                  <div class="d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">{{ $transaction->wallet->customer->user->name }}</h6>
                    <small class="text-{{ $transaction->type === 'credit' ? 'success' : 'danger' }} fw-medium">
                      {{ $transaction->type === 'credit' ? '+' : '-' }}{{ number_format($transaction->amount, 2) }}
                      {{ __('currency.sar') }}
                    </small>
                  </div>
                  <small class="text-muted">{{ $transaction->created_at->diffForHumans() }}</small>
                </div>
              </div>
            @endforeach
          @else
            <div class="text-center py-4">
              <i class="bx bx-receipt bx-lg text-muted mb-2"></i>
              <p class="text-muted mb-0">{{ __('dashboard.recent_transactions.no_transactions') }}</p>
            </div>
          @endif
        </div>
      </div>
    </div>

    <!-- Today's Summary -->
    <div class="col-xl-6 mb-4">
      <div class="card h-100">
        <div class="card-header">
          <h5 class="card-title mb-0">{{ __('dashboard.today_summary.title') }}</h5>
        </div>
        <div class="card-body">
          <div class="row g-4">
            <div class="col-6">
              <div class="d-flex align-items-center">
                <div class="avatar avatar-sm me-3">
                  <div class="avatar-initial bg-label-primary rounded">
                    <i class="bx bx-shopping-bag bx-sm"></i>
                  </div>
                </div>
                <div>
                  <h4 class="mb-0">{{ $todayOrders }}</h4>
                  <small class="text-muted">{{ __('dashboard.today_summary.orders') }}</small>
                </div>
              </div>
            </div>
            <div class="col-6">
              <div class="d-flex align-items-center">
                <div class="avatar avatar-sm me-3">
                  <div class="avatar-initial bg-label-success rounded">
                    <i class="bx bx-user bx-sm"></i>
                  </div>
                </div>
                <div>
                  <h4 class="mb-0">{{ $todayUsers }}</h4>
                  <small class="text-muted">{{ __('dashboard.today_summary.new_users') }}</small>
                </div>
              </div>
            </div>
            <div class="col-6">
              <div class="d-flex align-items-center">
                <div class="avatar avatar-sm me-3">
                  <div class="avatar-initial bg-label-info rounded">
                    <i class="bx bx-transfer bx-sm"></i>
                  </div>
                </div>
                <div>
                  <h4 class="mb-0">{{ $todayTransactions }}</h4>
                  <small class="text-muted">{{ __('dashboard.today_summary.transactions') }}</small>
                </div>
              </div>
            </div>
            <div class="col-6">
              <div class="d-flex align-items-center">
                <div class="avatar avatar-sm me-3">
                  <div class="avatar-initial bg-label-warning rounded">
                    <i class="bx bx-time bx-sm"></i>
                  </div>
                </div>
                <div>
                  <h4 class="mb-0">{{ $pendingOrders }}</h4>
                  <small class="text-muted">{{ __('dashboard.today_summary.pending_orders') }}</small>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
