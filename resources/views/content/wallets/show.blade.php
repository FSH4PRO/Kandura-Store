@extends('layouts.contentNavbarLayout')

@section('title', __('wallets.show.title'))

@section('content')
  {{-- Header --}}
  <div class="row">
    <div class="col-12 mb-4">
      <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
          <h4 class="mb-1">{{ __('wallets.show.heading') }} #{{ $wallet->id }}</h4>
          <p class="mb-0 text-muted">{{ __('wallets.show.subheading') }}</p>
        </div>
        <div>
          <a href="{{ route('admin.wallets.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bx bx-arrow-back me-1"></i> {{ __('wallets.show.back') }}
          </a>
        </div>
      </div>
    </div>
  </div>

  {{-- Success/Error Messages --}}
  @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <i class="bx bx-check-circle me-2"></i>
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  @if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <i class="bx bx-error-circle me-2"></i>
      {{ session('error') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  <div class="row">
    {{-- Wallet Info --}}
    <div class="col-lg-4 col-md-5 mb-4">
      <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
          <h5 class="mb-0">{{ __('wallets.show.wallet_info') }}</h5>
          @if ($wallet->is_active)
            <span class="badge bg-label-success">{{ __('wallets.show.status_active') }}</span>
          @else
            <span class="badge bg-label-danger">{{ __('wallets.show.status_inactive') }}</span>
          @endif
        </div>
        <div class="card-body">

          <ul class="list-unstyled mb-4">
            <li class="mb-3 d-flex justify-content-between align-items-center">
              <span class="text-muted">{{ __('wallets.show.customer') }}</span>
              <span class="fw-medium text-end">
                {{ $wallet->customer->user->name ?? ($wallet->customer->phone ?? '-') }}
                @if ($wallet->customer->phone && $wallet->customer->user?->name)
                  <br><small class="text-muted">{{ $wallet->customer->phone }}</small>
                @endif
              </span>
            </li>

            <div class="mb-4 p-3 bg-label-primary rounded">
              <label class="text-muted small d-block mb-2">{{ __('wallets.show.balance') }}</label>
              <p class="mb-0">
                <strong class="fs-3 text-primary">{{ number_format((float) $wallet->balance, 2) }}</strong>
                <small class="text-muted ms-1">{{ $wallet->currency ?? 'AED' }}</small>
              </p>
            </div>

            <li class="mb-3 d-flex justify-content-between">
              <span class="text-muted">{{ __('wallets.show.created_at') }}</span>
              <span class="fw-medium">{{ $wallet->created_at?->format('Y-m-d H:i') }}</span>
            </li>
            <li class="mb-3 d-flex justify-content-between">
              <span class="text-muted">{{ __('wallets.show.updated_at') }}</span>
              <span class="fw-medium">{{ $wallet->updated_at?->format('Y-m-d H:i') }}</span>
            </li>
          </ul>

          <hr>

          {{-- Actions --}}
          <div class="d-grid gap-2">
            @can('addCredit', $wallet)
              <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#topupModal">
                <i class="bx bx-plus me-1"></i> {{ __('wallets.show.add_credit') }}
              </button>
            @endcan

            @can('manageStatus', $wallet)
              @if ($wallet->is_active)
                <form action="{{ route('admin.wallets.deactivate', $wallet->id) }}" method="POST">
                  @csrf
                  @method('PATCH')
                  <button type="submit" class="btn btn-warning w-100"
                    onclick="return confirm('{{ __('wallets.messages.confirm_deactivate') }}');">
                    <i class="bx bx-x me-1"></i> {{ __('wallets.show.deactivate') }}
                  </button>
                </form>
              @else
                <form action="{{ route('admin.wallets.activate', $wallet->id) }}" method="POST">
                  @csrf
                  @method('PATCH')
                  <button type="submit" class="btn btn-success w-100">
                    <i class="bx bx-check me-1"></i> {{ __('wallets.show.activate') }}
                  </button>
                </form>
              @endif
            @endcan
          </div>
        </div>
      </div>
    </div>

    {{-- Transactions --}}
    <div class="col-lg-8 col-md-7">
      <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
          <h5 class="mb-0">{{ __('wallets.show.transactions') }}</h5>
          <span class="badge bg-label-primary">{{ $wallet->transactions->count() }}
            {{ __('wallets.show.transactions_count') }}</span>
        </div>
        <div class="card-body">
          <div class="table-responsive text-nowrap">
            <table class="table table-hover mb-0">
              <thead>
                <tr>
                  <th>{{ __('wallets.transactions.type') }}</th>
                  <th>{{ __('wallets.transactions.amount') }}</th>
                  <th>{{ __('wallets.transactions.description') }}</th>
                  <th>{{ __('wallets.transactions.date') }}</th>
                </tr>
              </thead>
              <tbody>
                @forelse ($wallet->transactions as $transaction)
                  <tr>
                    <td>
                      @if ($transaction->isCredit())
                        <span class="badge bg-label-success">
                          <i class="bx bx-up-arrow-alt me-1"></i>{{ __('wallets.transactions.credit') }}
                        </span>
                      @else
                        <span class="badge bg-label-danger">
                          <i class="bx bx-down-arrow-alt me-1"></i>{{ __('wallets.transactions.debit') }}
                        </span>
                      @endif
                    </td>
                    <td>
                      <strong class="{{ $transaction->isCredit() ? 'text-success' : 'text-danger' }}">
                        {{ $transaction->isCredit() ? '+' : '-' }}{{ number_format((float) $transaction->amount, 2) }}
                      </strong>
                      <small class="text-muted">{{ $wallet->currency ?? 'AED' }}</small>
                    </td>
                    <td>
                      <span class="text-truncate d-inline-block" style="max-width: 200px;"
                        title="{{ $transaction->description }}">
                        {{ $transaction->description }}
                      </span>
                      @if ($transaction->order_id)
                        <br><small class="text-muted">Order #{{ $transaction->order_id }}</small>
                      @endif
                    </td>
                    <td>
                      <small>{{ $transaction->created_at?->format('Y-m-d') }}</small>
                      <br><small class="text-muted">{{ $transaction->created_at?->format('H:i') }}</small>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="4" class="text-center text-muted py-4">
                      {{ __('wallets.transactions.empty') }}
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Topup Modal --}}
  @can('addCredit', $wallet)
    <div class="modal fade" id="topupModal" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <form action="{{ route('admin.wallets.topup', $wallet->id) }}" method="POST">
            @csrf
            <div class="modal-header">
              <h5 class="modal-title">{{ __('wallets.modal.topup_title') }}</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="mb-3">
                <label class="form-label">{{ __('wallets.modal.amount') }} <span class="text-danger">*</span></label>
                <input type="number" step="0.01" min="0.01" name="amount" class="form-control" required>
              </div>
              <div class="mb-3">
                <label class="form-label">{{ __('wallets.modal.reference') }}</label>
                <input type="text" name="reference" class="form-control" maxlength="255">
              </div>
              <div class="mb-3">
                <label class="form-label">{{ __('wallets.modal.note') }}</label>
                <textarea name="note" class="form-control" rows="3" maxlength="500"></textarea>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary"
                data-bs-dismiss="modal">{{ __('wallets.modal.cancel') }}</button>
              <button type="submit" class="btn btn-primary">{{ __('wallets.modal.submit') }}</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  @endcan
@endsection
