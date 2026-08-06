@extends('layouts.layoutMaster')

@section('title', __('dashboard.transactions.detail.title'))

@section('content')
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="card-title mb-0">{{ __('dashboard.transactions.detail.title') }} #{{ $transaction->id }}</h5>
          <a href="{{ route('dashboard.transactions.index') }}" class="btn btn-outline-primary">
            <i class="bx bx-arrow-back me-1"></i>{{ __('dashboard.transactions.detail.back_to_list') }}
          </a>
        </div>

        <div class="card-body">
          <div class="row">
            <!-- Transaction Details -->
            <div class="col-md-8">
              <div class="card">
                <div class="card-header">
                  <h6 class="card-title mb-0">{{ __('dashboard.transactions.detail.transaction_info') }}</h6>
                </div>
                <div class="card-body">
                  <div class="row g-3">
                    <div class="col-md-6">
                      <label class="form-label fw-medium">{{ __('dashboard.transactions.detail.id') }}</label>
                      <p class="mb-0">{{ $transaction->id }}</p>
                    </div>
                    <div class="col-md-6">
                      <label class="form-label fw-medium">{{ __('dashboard.transactions.detail.type') }}</label>
                      <p class="mb-0">
                        <span class="badge bg-label-{{ $transaction->type === 'credit' ? 'success' : 'danger' }}">
                          {{ __('dashboard.transactions.types.' . $transaction->type) }}
                        </span>
                      </p>
                    </div>
                    <div class="col-md-6">
                      <label class="form-label fw-medium">{{ __('dashboard.transactions.detail.amount') }}</label>
                      <p class="mb-0 fw-medium {{ $transaction->type === 'credit' ? 'text-success' : 'text-danger' }}">
                        {{ $transaction->type === 'credit' ? '+' : '-' }}{{ number_format($transaction->amount, 2) }}
                        {{ __('currency.sar') }}
                      </p>
                    </div>
                    <div class="col-md-6">
                      <label class="form-label fw-medium">{{ __('dashboard.transactions.detail.created_at') }}</label>
                      <p class="mb-0">{{ $transaction->created_at->format('d/m/Y H:i:s') }}</p>
                    </div>
                    <div class="col-md-6">
                      <label class="form-label fw-medium">{{ __('dashboard.transactions.detail.updated_at') }}</label>
                      <p class="mb-0">{{ $transaction->updated_at->format('d/m/Y H:i:s') }}</p>
                    </div>
                    @if ($transaction->description)
                      <div class="col-12">
                        <label class="form-label fw-medium">{{ __('dashboard.transactions.detail.description') }}</label>
                        <p class="mb-0">{{ $transaction->description }}</p>
                      </div>
                    @endif
                    @if ($transaction->reference_id)
                      <div class="col-12">
                        <label
                          class="form-label fw-medium">{{ __('dashboard.transactions.detail.reference_id') }}</label>
                        <p class="mb-0">{{ $transaction->reference_id }}</p>
                      </div>
                    @endif
                  </div>
                </div>
              </div>
            </div>

            <!-- Customer Information -->
            <div class="col-md-4">
              <div class="card">
                <div class="card-header">
                  <h6 class="card-title mb-0">{{ __('dashboard.transactions.detail.customer_info') }}</h6>
                </div>
                <div class="card-body">
                  <div class="d-flex align-items-center mb-3">
                    @if ($transaction->wallet->customer->user->getFirstMediaUrl('profile'))
                      <img src="{{ $transaction->wallet->customer->user->getFirstMediaUrl('profile') }}" alt="Avatar"
                        class="rounded-circle me-3" width="48" height="48">
                    @else
                      <div class="avatar avatar-md me-3">
                        <span
                          class="avatar-initial rounded-circle bg-label-primary">{{ substr($transaction->wallet->customer->user->name, 0, 1) }}</span>
                      </div>
                    @endif
                    <div>
                      <h6 class="mb-0">{{ $transaction->wallet->customer->user->name }}</h6>
                      <small class="text-muted">{{ $transaction->wallet->customer->user->email }}</small>
                    </div>
                  </div>

                  <div class="row g-2">
                    <div class="col-12">
                      <label
                        class="form-label fw-medium">{{ __('dashboard.transactions.detail.wallet_balance') }}</label>
                      <p class="mb-0 fw-medium">{{ number_format($transaction->wallet->balance, 2) }}
                        {{ __('currency.sar') }}</p>
                    </div>
                    <div class="col-12">
                      <label
                        class="form-label fw-medium">{{ __('dashboard.transactions.detail.customer_since') }}</label>
                      <p class="mb-0">{{ $transaction->wallet->customer->user->created_at->format('d/m/Y') }}</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
