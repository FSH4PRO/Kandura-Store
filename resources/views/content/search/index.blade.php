@extends('layouts.contentNavbarLayout')

@section('title', __('search.index.title'))

@section('content')
  {{-- Header --}}
  <div class="row">
    <div class="col-12 mb-4">
      <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
          <h4 class="mb-1">{{ __('search.index.heading') }}</h4>
          <p class="mb-0 text-muted">
            {{ __('search.index.subheading', ['query' => $query, 'count' => $totalResults]) }}
          </p>
        </div>
      </div>
    </div>
  </div>

  {{-- Search Form --}}
  <div class="row mb-4">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <form action="{{ route('admin.search.index') }}" method="GET" class="row g-3">
            <div class="col-md-10">
              <input type="text" name="q" class="form-control" placeholder="{{ __('search.form.placeholder') }}"
                value="{{ $query }}" autofocus>
            </div>
            <div class="col-md-2">
              <button type="submit" class="btn btn-primary w-100">
                <i class="bx bx-search me-1"></i> {{ __('search.form.submit') }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  @if (empty($query))
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-body text-center py-5">
            <i class="bx bx-search icon-lg text-muted mb-3"></i>
            <p class="text-muted">{{ __('search.index.empty_query') }}</p>
          </div>
        </div>
      </div>
    </div>
  @elseif ($totalResults === 0)
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-body text-center py-5">
            <i class="bx bx-search icon-lg text-muted mb-3"></i>
            <p class="text-muted">{{ __('search.index.no_results', ['query' => $query]) }}</p>
          </div>
        </div>
      </div>
    </div>
  @else
    {{-- Orders Results --}}
    @if (count($results['orders']) > 0)
      <div class="row mb-4">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h5 class="mb-0">
                <i class="bx bx-cart me-2"></i> {{ __('search.results.orders') }}
                <span class="badge bg-label-primary">{{ count($results['orders']) }}</span>
              </h5>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th>{{ __('search.table.customer') }}</th>
                      <th>{{ __('search.table.total') }}</th>
                      <th>{{ __('search.table.status') }}</th>
                      <th>{{ __('search.table.actions') }}</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($results['orders'] as $order)
                      @php
                        $customer = $order->customer;
                        $user = $customer?->user;
                        $statusValue = $order->status->value ?? $order->status;
                        $statusClass = match ($statusValue) {
                            'pending' => 'bg-label-warning',
                            'accepted' => 'bg-label-info',
                            'paid' => 'bg-label-success',
                            'rejected', 'canceled' => 'bg-label-danger',
                            default => 'bg-label-secondary',
                        };
                      @endphp
                      <tr>
                        <td>{{ $user?->name ?? ($customer?->phone ?? '-') }}</td>
                        <td>{{ number_format((float) $order->total_amount, 2) }} {{ $order->currency ?? 'AED' }}</td>
                        <td>
                          <span class="badge {{ $statusClass }}">
                            @php
                              $statusKey = is_string($statusValue) ? $statusValue : $statusValue->value;
                            @endphp
                            {{ __('orders.statuses.' . $statusKey) }}
                          </span>
                        </td>
                        <td>
                          <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary">
                            {{ __('search.table.view') }}
                          </a>
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    @endif

    {{-- Customers Results --}}
    @if (count($results['customers']) > 0)
      <div class="row mb-4">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h5 class="mb-0">
                <i class="bx bx-user me-2"></i> {{ __('search.results.customers') }}
                <span class="badge bg-label-primary">{{ count($results['customers']) }}</span>
              </h5>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th>{{ __('search.table.profile_picture') }}</th>
                      <th>{{ __('search.table.name') }}</th>
                      <th>{{ __('search.table.phone') }}</th>
                      <th>{{ __('search.table.actions') }}</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($results['customers'] as $customer)
                      @php
                        $user = $customer->user;
                      @endphp
                      <tr>
                        <td>
                          <img
                            src="{{ $user?->getFirstMediaUrl('profile_image') ?: asset('assets/img/avatars/default.png') }}"
                            alt="Profile Picture" class="rounded-circle" width="40" height="40">
                        </td>
                        <td>{{ $user?->name ?? '-' }}</td>
                        <td>{{ $customer->phone }}</td>
                        <td>
                          <a href="{{ route('users.index', ['search' => $query]) }}"
                            class="btn btn-sm btn-outline-primary">
                            {{ __('search.table.view') }}
                          </a>
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    @endif

    {{-- Wallets Results --}}
    @if (count($results['wallets']) > 0)
      <div class="row mb-4">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h5 class="mb-0">
                <i class="bx bx-wallet me-2"></i> {{ __('search.results.wallets') }}
                <span class="badge bg-label-primary">{{ count($results['wallets']) }}</span>
              </h5>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th>{{ __('search.table.customer') }}</th>
                      <th>{{ __('search.table.balance') }}</th>
                      <th>{{ __('search.table.status') }}</th>
                      <th>{{ __('search.table.actions') }}</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($results['wallets'] as $wallet)
                      @php
                        $customer = $wallet->customer;
                        $user = $customer?->user;
                      @endphp
                      <tr>
                        <td>{{ $user?->name ?? ($customer?->phone ?? '-') }}</td>
                        <td>{{ number_format((float) $wallet->balance, 2) }} {{ $wallet->currency ?? 'AED' }}</td>
                        <td>
                          @if ($wallet->is_active)
                            <span class="badge bg-label-success">{{ __('wallets.table.status_active') }}</span>
                          @else
                            <span class="badge bg-label-danger">{{ __('wallets.table.status_inactive') }}</span>
                          @endif
                        </td>
                        <td>
                          <a href="{{ route('admin.wallets.show', $wallet->id) }}"
                            class="btn btn-sm btn-outline-primary">
                            {{ __('search.table.view') }}
                          </a>
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    @endif

    {{-- Designs Results --}}
    @if (count($results['designs']) > 0)
      <div class="row mb-4">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h5 class="mb-0">
                <i class="bx bx-image me-2"></i> {{ __('search.results.designs') }}
                <span class="badge bg-label-primary">{{ count($results['designs']) }}</span>
              </h5>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th>{{ __('search.table.name') }}</th>
                      <th>{{ __('search.table.customer') }}</th>
                      <th>{{ __('search.table.price') }}</th>
                      <th>{{ __('search.table.actions') }}</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($results['designs'] as $design)
                      @php
                        $customer = $design->customer;
                        $user = $customer?->user;
                        $locale = app()->getLocale();
                        $name = is_array($design->name)
                            ? $design->name[$locale] ?? ($design->name['en'] ?? '')
                            : $design->name;
                      @endphp
                      <tr>
                        <td>{{ $name }}</td>
                        <td>{{ $user?->name ?? ($customer?->phone ?? '-') }}</td>
                        <td>{{ number_format((float) $design->price, 2) }} AED</td>
                        <td>
                          <a href="{{ route('admin.designs.show', $design->id) }}"
                            class="btn btn-sm btn-outline-primary">
                            {{ __('search.table.view') }}
                          </a>
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    @endif
  @endif
@endsection

@push('page-script')
  <script>
    // Auto-focus and select search input
    document.addEventListener('DOMContentLoaded', function() {
      const searchInput = document.querySelector('input[name="q"]');
      if (searchInput) {
        searchInput.focus();
        // Select all text if there's a query
        if (searchInput.value) {
          searchInput.select();
        }
      }
    });
  </script>
@endpush
