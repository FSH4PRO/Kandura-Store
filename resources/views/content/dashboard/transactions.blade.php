@extends('layouts.layoutMaster')

@section('title', __('dashboard.transactions.title'))

@section('vendor-style')
  @vite(['resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss', 'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss', 'resources/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss'])
@endsection

@section('vendor-script')
  @vite(['resources/assets/vendor/libs/moment/moment.js', 'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js'])
@endsection

@section('page-script')
  @vite('resources/assets/js/transactions.js')
@endsection

@section('content')
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="card-title mb-0">{{ __('dashboard.transactions.title') }}</h5>
          <div class="d-flex gap-2">
            <button type="button" class="btn btn-outline-primary" data-bs-toggle="collapse"
              data-bs-target="#filtersCollapse">
              <i class="bx bx-filter-alt me-1"></i>{{ __('dashboard.transactions.filters.title') }}
            </button>
          </div>
        </div>

        <!-- Filters -->
        <div class="collapse" id="filtersCollapse">
          <div class="card-body border-bottom">
            <form id="filtersForm" class="row g-3">
              <div class="col-md-3">
                <label for="typeFilter" class="form-label">{{ __('dashboard.transactions.filters.type') }}</label>
                <select class="form-select" id="typeFilter" name="type">
                  <option value="">{{ __('dashboard.transactions.filters.all_types') }}</option>
                  <option value="credit">{{ __('dashboard.transactions.filters.credit') }}</option>
                  <option value="debit">{{ __('dashboard.transactions.filters.debit') }}</option>
                </select>
              </div>
              <div class="col-md-3">
                <label for="dateFromFilter"
                  class="form-label">{{ __('dashboard.transactions.filters.date_from') }}</label>
                <input type="date" class="form-control" id="dateFromFilter" name="date_from">
              </div>
              <div class="col-md-3">
                <label for="dateToFilter" class="form-label">{{ __('dashboard.transactions.filters.date_to') }}</label>
                <input type="date" class="form-control" id="dateToFilter" name="date_to">
              </div>
              <div class="col-12">
                <div class="d-flex gap-2">
                  <button type="submit" class="btn btn-primary">{{ __('dashboard.transactions.filters.apply') }}</button>
                  <button type="button" class="btn btn-outline-secondary"
                    id="clearFilters">{{ __('dashboard.transactions.filters.clear') }}</button>
                </div>
              </div>
            </form>
          </div>
        </div>

        <!-- Table -->
        <div class="card-datatable table-responsive">
          <table class="datatables-transactions table">
            <thead class="table-light">
              <tr>
                <th>{{ __('dashboard.transactions.table.id') }}</th>
                <th>{{ __('dashboard.transactions.table.customer') }}</th>
                <th>{{ __('dashboard.transactions.table.type') }}</th>
                <th>{{ __('dashboard.transactions.table.amount') }}</th>
                <th>{{ __('dashboard.transactions.table.date') }}</th>
                <th>{{ __('dashboard.transactions.table.actions') }}</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($transactions as $transaction)
                <tr>
                  <td>{{ $transaction->id }}</td>
                  <td>
                    <div class="d-flex align-items-center">
                      @if ($transaction->wallet->customer->user->getFirstMediaUrl('profile'))
                        <img src="{{ $transaction->wallet->customer->user->getFirstMediaUrl('profile') }}" alt="Avatar"
                          class="rounded-circle me-2" width="32" height="32">
                      @else
                        <div class="avatar avatar-sm me-2">
                          <span
                            class="avatar-initial rounded-circle bg-label-primary">{{ substr($transaction->wallet->customer->user->name, 0, 1) }}</span>
                        </div>
                      @endif
                      <div>
                        <div class="fw-medium">{{ $transaction->wallet->customer->user->name }}</div>
                        <small class="text-muted">{{ $transaction->wallet->customer->user->email }}</small>
                      </div>
                    </div>
                  </td>
                  <td>
                    <span class="badge bg-label-{{ $transaction->type === 'credit' ? 'success' : 'danger' }}">
                      {{ __('dashboard.transactions.types.' . $transaction->type) }}
                    </span>
                  </td>
                  <td>
                    <span class="fw-medium {{ $transaction->type === 'credit' ? 'text-success' : 'text-danger' }}">
                      {{ $transaction->type === 'credit' ? '+' : '-' }}{{ number_format($transaction->amount, 2) }}
                      {{ __('currency.sar') }}
                    </span>
                  </td>
                  <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                  <td>
                    <a href="{{ route('dashboard.transactions.show', $transaction) }}"
                      class="btn btn-sm btn-outline-primary">
                      <i class="bx bx-show me-1"></i>{{ __('dashboard.transactions.actions.view') }}
                    </a>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        @if ($transactions->hasPages())
          <div class="card-footer">
            {{ $transactions->appends(request()->query())->links() }}
          </div>
        @endif
      </div>
    </div>
  </div>

@endsection
