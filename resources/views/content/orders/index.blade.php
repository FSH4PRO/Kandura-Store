@extends('layouts.contentNavbarLayout')

@section('title', __('orders.index.title'))

@section('content')
  {{-- Header --}}
  <div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center flex-wrap gap-3">
      <div>
        <h4 class="mb-1">{{ __('orders.index.heading') }}</h4>
        <p class="mb-0 text-muted">{{ __('orders.index.subheading') }}</p>
      </div>
    </div>
  </div>

  {{-- Filters --}}
  <div class="row mb-4">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <form method="GET" action="{{ route('admin.orders.index') }}" class="row g-3 align-items-end">

            {{-- Search --}}
            <div class="col-md-4">
              <label class="form-label">{{ __('orders.filters.search_label') }}</label>
              <input type="text" name="search" class="form-control"
                placeholder="{{ __('orders.filters.search_placeholder') }}" value="{{ $filters['search'] ?? '' }}">
            </div>

            {{-- Status --}}
            <div class="col-md-3">
              <label class="form-label">{{ __('orders.filters.status_label') }}</label>
              <select name="status" class="form-select">
                <option value="">{{ __('orders.filters.status_all') }}</option>
                @foreach ($statusOptions as $status)
                  <option value="{{ $status->value }}"
                    {{ ($filters['status'] ?? '') === $status->value ? 'selected' : '' }}>
                    {{ __('orders.statuses.' . $status->value) }}
                  </option>
                @endforeach
              </select>
            </div>

            {{-- Min total --}}
            <div class="col-md-2">
              <label class="form-label">{{ __('orders.filters.total_min') }}</label>
              <input type="number" step="0.01" name="total_min" class="form-control"
                value="{{ $filters['total_min'] ?? '' }}">
            </div>

            {{-- Max total --}}
            <div class="col-md-2">
              <label class="form-label">{{ __('orders.filters.total_max') }}</label>
              <input type="number" step="0.01" name="total_max" class="form-control"
                value="{{ $filters['total_max'] ?? '' }}">
            </div>

            <div class="col-md-3 d-flex gap-2">
              <button type="submit" class="btn btn-primary flex-grow-1">
                {{ __('orders.filters.submit') }}
              </button>
              <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
                {{ __('orders.filters.reset') }}
              </a>
            </div>

          </form>
        </div>
      </div>
    </div>
  </div>

  {{-- Table --}}
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="table-responsive text-nowrap">
          <table class="table table-hover mb-0">
            <thead>
              <tr>
                <th>#</th>
                <th>{{ __('orders.table.customer') }}</th>
                <th>{{ __('orders.table.total') }}</th>
                <th>{{ __('orders.table.status') }}</th>
                <th>{{ __('orders.table.created_at') }}</th>
                <th class="text-center">{{ __('orders.table.actions') }}</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($orders as $order)
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
                  <td>#{{ $order->id }}</td>
                  <td>{{ $user?->name ?? '-' }}</td>
                  <td>
                    {{ number_format($order->total, 2) }}
                    @if (!empty($order->currency))
                      <small class="text-muted">{{ $order->currency }}</small>
                    @endif
                  </td>
                  <td>
                    <span class="badge {{ $statusClass }}">
                      {{ __('orders.statuses.' . $statusValue) }}
                    </span>
                  </td>
                  <td>{{ $order->created_at?->format('Y-m-d H:i') }}</td>
                  <td class="text-center">
                    <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary">
                      {{ __('orders.table.view') }}
                    </a>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="6" class="text-center text-muted py-4">
                    {{ __('orders.table.empty') }}
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        @if ($orders instanceof \Illuminate\Contracts\Pagination\Paginator)
          <div class="card-footer">
            {{ $orders->links() }}
          </div>
        @endif
      </div>
    </div>
  </div>
@endsection
