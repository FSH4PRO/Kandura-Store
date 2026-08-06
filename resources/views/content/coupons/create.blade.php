@extends('layouts.contentNavbarLayout')

@section('title', __('coupons.create_coupon'))

@section('content')
  <div class="row">
    <div class="col-12">
      <div class="card">

        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="mb-0">{{ __('coupons.create_coupon') }}</h5>
          <a href="{{ route('coupons.index') }}" class="btn btn-outline-secondary">
            <i class="bx bx-left-arrow-alt me-1"></i>{{ __('coupons.back') }}
          </a>
        </div>

        <div class="card-body">
          @if ($errors->any())
            <div class="alert alert-danger">
              <ul class="mb-0">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <form method="POST" action="{{ route('coupons.store') }}">
            @csrf

            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label">{{ __('coupons.code') }}</label>
                <input type="text" name="code" class="form-control" value="{{ old('code') }}"
                  placeholder="{{ __('coupons.unique_code_help') }}">
              </div>

              <div class="col-md-6">
                <label class="form-label">{{ __('coupons.type') }}</label>
                <select name="type" class="form-select">
                  <option value="percent" {{ old('type') === 'percent' ? 'selected' : '' }}>{{ __('coupons.percent') }}
                  </option>
                  <option value="fixed" {{ old('type') === 'fixed' ? 'selected' : '' }}>{{ __('coupons.fixed') }}
                  </option>
                </select>
              </div>

              <div class="col-md-6">
                <label class="form-label">{{ __('coupons.amount') }}</label>
                <input type="number" step="0.01" name="amount" class="form-control" value="{{ old('amount') }}"
                  placeholder="{{ __('coupons.enter_discount_amount') }}">
                <div class="form-text">{{ __('coupons.amount_hint') }}</div>
              </div>

              <div class="col-md-6">
                <label class="form-label">{{ __('coupons.maximum_uses') }}</label>
                <input type="number" name="max_uses" class="form-control" value="{{ old('max_uses') }}"
                  placeholder="{{ __('coupons.unlimited_uses_help') }}">
              </div>

              <div class="col-md-6">
                <label class="form-label">{{ __('coupons.start_date') }}</label>
                <input type="datetime-local" name="starts_at" class="form-control" value="{{ old('starts_at', now()->format('Y-m-d\TH:i')) }}">
                <div class="form-text">{{ __('coupons.start_immediately_help') }}</div>
              </div>

              <div class="col-md-6">
                <label class="form-label">{{ __('coupons.end_date') }}</label>
                <input type="datetime-local" name="ends_at" class="form-control" value="{{ old('ends_at') }}">
                <div class="form-text">{{ __('coupons.no_expiration_help') }}</div>
              </div>

              <div class="col-md-12">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" value="1" id="is_active" name="is_active"
                    {{ old('is_active', 1) ? 'checked' : '' }}>
                  <label class="form-check-label" for="is_active">
                    {{ __('coupons.active') }}
                  </label>
                </div>
              </div>

              <div class="col-md-12">
                <label class="form-label">{{ __('coupons.allowed_customers_optional') }}</label>
                <select name="allowed_customers[]" class="form-select" multiple>
                  @foreach ($customers ?? [] as $customer)
                    <option value="{{ $customer->id }}"
                      {{ collect(old('allowed_customers', []))->contains($customer->id) ? 'selected' : '' }}>
                      #{{ $customer->id }} - {{ $customer->user?->name ?? ($customer->email ?? 'Customer') }}
                    </option>
                  @endforeach
                </select>
                <div class="form-text">{{ __('coupons.allow_all_customers_help') }}</div>
              </div>

            </div>

            <div class="mt-4">
              @can('coupons.create')
                <button type="submit" class="btn btn-primary">
                  <i class="bx bx-save me-1"></i>{{ __('coupons.save') }}
                </button>
              @endcan
              <a href="{{ route('coupons.index') }}" class="btn btn-outline-secondary">
                {{ __('coupons.cancel') }}
              </a>
            </div>

          </form>
        </div>

      </div>
    </div>
  </div>
@endsection
