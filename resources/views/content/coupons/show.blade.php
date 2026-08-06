@extends('layouts.contentNavbarLayout')

@section('title', __('coupons.coupon_details'))

@section('content')
  <div class="row">
    <div class="col-12">
      <div class="card">

        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="mb-0">{{ __('coupons.coupon_details') }}: <strong>{{ $coupon->code }}</strong></h5>
          <div class="d-flex gap-2">
            @can('coupons.edit')
              <a href="{{ route('coupons.edit', $coupon) }}" class="btn btn-primary">
                <i class="bx bx-edit-alt me-1"></i>{{ __('coupons.edit') }}
              </a>
            @endcan
            <a href="{{ route('coupons.index') }}" class="btn btn-outline-secondary">
              <i class="bx bx-left-arrow-alt me-1"></i>{{ __('coupons.back') }}
            </a>
          </div>
        </div>

        <div class="card-body">
          <div class="row g-3">
            <div class="col-md-4">
              <div class="p-3 border rounded">
                <div class="text-muted">{{ __('coupons.code') }}</div>
                <div class="fw-bold">{{ $coupon->code }}</div>
              </div>
            </div>

            <div class="col-md-4">
              <div class="p-3 border rounded">
                <div class="text-muted">{{ __('coupons.type') }}</div>
                <div class="fw-bold">
                  {{ $coupon->type->value === 'percent' ? __('coupons.percent') : __('coupons.fixed') }}
                </div>
              </div>
            </div>

            <div class="col-md-4">
              <div class="p-3 border rounded">
                <div class="text-muted">{{ __('coupons.amount') }}</div>
                <div class="fw-bold">
                  {{ $coupon->type->value === 'percent' ? $coupon->amount . '%' : number_format($coupon->amount, 2) }}
                </div>
              </div>
            </div>

            <div class="col-md-4">
              <div class="p-3 border rounded">
                <div class="text-muted">{{ __('coupons.status') }}</div>
                <div class="fw-bold">
                  @if ($coupon->is_active)
                    <span class="badge bg-success">{{ __('coupons.active') }}</span>
                  @else
                    <span class="badge bg-danger">{{ __('coupons.inactive') }}</span>
                  @endif
                </div>
              </div>
            </div>

            <div class="col-md-4">
              <div class="p-3 border rounded">
                <div class="text-muted">{{ __('coupons.valid_from') }}</div>
                <div class="fw-bold">
                  {{ $coupon->starts_at ? $coupon->starts_at->format('Y-m-d H:i') : __('coupons.immediate') }}
                </div>
              </div>
            </div>

            <div class="col-md-4">
              <div class="p-3 border rounded">
                <div class="text-muted">{{ __('coupons.valid_until') }}</div>
                <div class="fw-bold">
                  {{ $coupon->ends_at ? $coupon->ends_at->format('Y-m-d H:i') : __('coupons.no_limit') }}
                </div>
              </div>
            </div>

            <div class="col-md-4">
              <div class="p-3 border rounded">
                <div class="text-muted">{{ __('coupons.maximum_uses') }}</div>
                <div class="fw-bold">
                  {{ $coupon->max_uses ?? __('coupons.unlimited') }}
                </div>
              </div>
            </div>

            <div class="col-md-4">
              <div class="p-3 border rounded">
                <div class="text-muted">{{ __('coupons.total_uses') }}</div>
                <div class="fw-bold">{{ $coupon->redemptions_count ?? 0 }}</div>
              </div>
            </div>

            <div class="col-md-4">
              <div class="p-3 border rounded">
                <div class="text-muted">{{ __('coupons.remaining_uses') }}</div>
                <div class="fw-bold">
                  @if ($coupon->max_uses)
                    {{ max(0, $coupon->max_uses - ($coupon->redemptions_count ?? 0)) }}
                  @else
                    —
                  @endif
                </div>
              </div>
            </div>

            <div class="col-12 mt-2">
              <h6 class="mb-2">{{ __('coupons.allowed_customers') }}</h6>
              @php $allowed = $coupon->allowedCustomers ?? collect(); @endphp
              @if ($allowed->count() === 0)
                <div class="text-muted">{{ __('coupons.allow_all_customers') }}</div>
              @else
                <ul class="mb-0">
                  @foreach ($allowed as $customer)
                    <li>#{{ $customer->id }} - {{ $customer->user?->name ?? ($customer->email ?? 'Customer') }}</li>
                  @endforeach
                </ul>
              @endif
            </div>

          </div>
        </div>

      </div>
    </div>
  </div>
@endsection
