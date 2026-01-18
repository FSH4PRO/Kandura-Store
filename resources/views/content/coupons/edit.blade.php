@extends('layouts.contentNavbarLayout')

@section('title', __('coupons.edit_coupon'))

@section('content')
  <div class="row">
    <div class="col-12">
      <div class="card">

        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="mb-0">{{ __('coupons.edit_coupon') }}: <strong>{{ $coupon->code }}</strong></h5>
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

          <form method="POST" action="{{ route('coupons.update', $coupon) }}">
            @csrf
            @method('PUT')

            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label">{{ __('coupons.code') }}</label>
                <input type="text" name="code" class="form-control" value="{{ old('code', $coupon->code) }}">
              </div>

              <div class="col-md-6">
                <label class="form-label">{{ __('coupons.type') }}</label>
                <select name="type" class="form-select">
                  <option value="percent" {{ old('type', $coupon->type->value) === 'percent' ? 'selected' : '' }}>
                    {{ __('coupons.percent') }}</option>
                  <option value="fixed" {{ old('type', $coupon->type->value) === 'fixed' ? 'selected' : '' }}>
                    {{ __('coupons.fixed') }}</option>
                </select>
              </div>

              <div class="col-md-6">
                <label class="form-label">{{ __('coupons.amount') }}</label>
                <input type="number" step="0.01" name="amount" class="form-control"
                  value="{{ old('amount', $coupon->amount) }}">
                <div class="form-text">{{ __('coupons.amount_hint') }}</div>
              </div>

              <div class="col-md-6">
                <label class="form-label">{{ __('coupons.maximum_uses') }}</label>
                <input type="number" name="max_uses" class="form-control"
                  value="{{ old('max_uses', $coupon->max_uses) }}"
                  placeholder="{{ __('coupons.unlimited_uses_help') }}">
              </div>

              <div class="col-md-6">
                <label class="form-label">{{ __('coupons.start_date') }}</label>
                <input type="datetime-local" name="starts_at" class="form-control"
                  value="{{ old('starts_at', optional($coupon->starts_at)->format('Y-m-d\TH:i')) }}">
              </div>

              <div class="col-md-6">
                <label class="form-label">{{ __('coupons.end_date') }}</label>
                <input type="datetime-local" name="ends_at" class="form-control"
                  value="{{ old('ends_at', optional($coupon->ends_at)->format('Y-m-d\TH:i')) }}">
              </div>

              <div class="col-md-12">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" value="1" id="is_active" name="is_active"
                    {{ old('is_active', $coupon->is_active) ? 'checked' : '' }}>
                  <label class="form-check-label" for="is_active">
                    {{ __('coupons.active') }}
                  </label>
                </div>
              </div>

              <div class="col-md-12">
                <label class="form-label">{{ __('coupons.allowed_customers_optional') }}</label>
                <select name="allowed_customers[]" class="form-select" multiple>
                  @php
                    $selected = collect(old('allowed_customers', $selectedCustomerIds ?? []))
                        ->map(fn($v) => (int) $v)
                        ->all();
                  @endphp
                  @foreach ($customers ?? [] as $customer)
                    <option value="{{ $customer->id }}"
                      {{ in_array($customer->id, $selected, true) ? 'selected' : '' }}>
                      #{{ $customer->id }} - {{ $customer->user?->name ?? ($customer->email ?? 'Customer') }}
                    </option>
                  @endforeach
                </select>
                <div class="form-text">{{ __('coupons.allow_all_customers_help') }}</div>
              </div>
            </div>

            <div class="mt-4">
              @can('coupons.edit')
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
