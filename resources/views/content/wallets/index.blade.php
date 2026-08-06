@extends('layouts.contentNavbarLayout')

@section('title', __('wallets.index.title'))

@section('content')
    {{-- Header --}}
    <div class="row">
        <div class="col-12 mb-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h4 class="mb-1">{{ __('wallets.index.heading') }}</h4>
                    <p class="mb-0 text-muted">{{ __('wallets.index.subheading') }}</p>
                </div>
                <div>
                    @can('bulkAddCredit', App\Models\Wallet::class)
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                            data-bs-target="#bulkTopupModal">
                            <i class="bx bx-plus me-1"></i> {{ __('wallets.index.bulk_topup') }}
                        </button>
                    @endcan
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

    {{-- Filters --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.wallets.index') }}" class="row g-3 align-items-end">

                        {{-- Search --}}
                        <div class="col-md-3">
                            <label class="form-label">{{ __('wallets.filters.search_label') }}</label>
                            <input type="text" name="search" class="form-control"
                                placeholder="{{ __('wallets.filters.search_placeholder') }}"
                                value="{{ $filters['search'] ?? '' }}">
                        </div>

                        {{-- Status --}}
                        <div class="col-md-2">
                            <label class="form-label">{{ __('wallets.filters.status_label') }}</label>
                            <select name="is_active" class="form-select">
                                <option value="">{{ __('wallets.filters.status_all') }}</option>
                                <option value="1" {{ ($filters['is_active'] ?? '') === '1' ? 'selected' : '' }}>
                                    {{ __('wallets.filters.status_active') }}
                                </option>
                                <option value="0" {{ ($filters['is_active'] ?? '') === '0' ? 'selected' : '' }}>
                                    {{ __('wallets.filters.status_inactive') }}
                                </option>
                            </select>
                        </div>

                        {{-- Min balance --}}
                        <div class="col-md-2">
                            <label class="form-label">{{ __('wallets.filters.balance_min') }}</label>
                            <input type="number" step="0.01" name="balance_min" class="form-control"
                                value="{{ $filters['balance_min'] ?? '' }}">
                        </div>

                        {{-- Max balance --}}
                        <div class="col-md-2">
                            <label class="form-label">{{ __('wallets.filters.balance_max') }}</label>
                            <input type="number" step="0.01" name="balance_max" class="form-control"
                                value="{{ $filters['balance_max'] ?? '' }}">
                        </div>

                        <div class="col-md-3 d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-grow-1">
                                {{ __('wallets.filters.submit') }}
                            </button>
                            <a href="{{ route('admin.wallets.index') }}" class="btn btn-outline-secondary">
                                {{ __('wallets.filters.reset') }}
                            </a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Wallets Table --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="table-responsive text-nowrap">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>{{ __('wallets.table.customer') }}</th>
                                <th>{{ __('wallets.table.balance') }}</th>
                                <th>{{ __('wallets.table.status') }}</th>
                                <th>{{ __('wallets.table.created_at') }}</th>
                                <th class="text-center">{{ __('wallets.table.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($wallets as $wallet)
                                @php
                                    $customer = $wallet->customer;
                                    $user = $customer?->user;
                                @endphp
                                <tr>
                                    <td>
                                        {{ $user?->name ?? ($customer?->phone ?? '-') }}
                                        @if ($customer?->phone)
                                            <br><small class="text-muted">{{ $customer->phone }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ number_format((float) $wallet->balance, 2) }}</strong>
                                        <small class="text-muted">{{ $wallet->currency ?? 'AED' }}</small>
                                    </td>
                                    <td>
                                        @if ($wallet->is_active)
                                            <span
                                                class="badge bg-label-success">{{ __('wallets.table.status_active') }}</span>
                                        @else
                                            <span
                                                class="badge bg-label-danger">{{ __('wallets.table.status_inactive') }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $wallet->created_at?->format('Y-m-d H:i') }}</td>
                                    <td class="text-center">
                                        <div class="dropdown">
                                            <button
                                                class="btn btn-sm btn-icon btn-text-secondary rounded-pill dropdown-toggle hide-arrow"
                                                type="button" data-bs-toggle="dropdown">
                                                <i class="bx bx-dots-vertical-rounded"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a href="{{ route('admin.wallets.show', $wallet->id) }}"
                                                    class="dropdown-item">
                                                    <i class="bx bx-show me-2"></i> {{ __('wallets.table.view') }}
                                                </a>
                                                @can('addCredit', $wallet)
                                                    <a href="#" class="dropdown-item" data-bs-toggle="modal"
                                                        data-bs-target="#topupModal{{ $wallet->id }}">
                                                        <i class="bx bx-plus me-2"></i> {{ __('wallets.table.add_credit') }}
                                                    </a>
                                                @endcan
                                                @can('manageStatus', $wallet)
                                                    @if ($wallet->is_active)
                                                        <form action="{{ route('admin.wallets.deactivate', $wallet->id) }}"
                                                            method="POST"
                                                            onsubmit="return confirm('{{ __('wallets.messages.confirm_deactivate') }}');">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="dropdown-item text-warning">
                                                                <i class="bx bx-x me-2"></i>
                                                                {{ __('wallets.table.deactivate') }}
                                                            </button>
                                                        </form>
                                                    @else
                                                        <form action="{{ route('admin.wallets.activate', $wallet->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="dropdown-item text-success">
                                                                <i class="bx bx-check me-2"></i>
                                                                {{ __('wallets.table.activate') }}
                                                            </button>
                                                        </form>
                                                    @endif
                                                @endcan
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        {{ __('wallets.table.empty') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($wallets instanceof \Illuminate\Contracts\Pagination\Paginator)
                    <div class="card-footer">
                        {{ $wallets->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Individual Topup Modals --}}
    @foreach ($wallets as $wallet)
        @can('addCredit', $wallet)
            <div class="modal fade" id="topupModal{{ $wallet->id }}" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="{{ route('admin.wallets.topup', $wallet->id) }}" method="POST">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title">
                                    {{ __('wallets.modal.topup_title') }} -
                                    {{ $wallet->customer?->user?->name ?? ($wallet->customer?->phone ?? 'N/A') }}
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">{{ __('wallets.modal.amount') }} <span
                                            class="text-danger">*</span></label>
                                    <input type="number" step="0.01" min="0.01" name="amount" class="form-control"
                                        required>
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
    @endforeach

    {{-- Bulk Topup Modal --}}
    @can('bulkAddCredit', App\Models\Wallet::class)
        <div class="modal fade" id="bulkTopupModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('admin.wallets.bulk-topup') }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">{{ __('wallets.modal.bulk_topup_title') }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">{{ __('wallets.modal.amount') }} <span
                                        class="text-danger">*</span></label>
                                <input type="number" step="0.01" min="0.01" name="amount" class="form-control"
                                    required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">{{ __('wallets.modal.reference') }}</label>
                                <input type="text" name="reference" class="form-control" maxlength="255">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">{{ __('wallets.modal.note') }}</label>
                                <textarea name="note" class="form-control" rows="3" maxlength="500"></textarea>
                            </div>
                            <div class="alert alert-warning">
                                <i class="bx bx-info-circle"></i> {{ __('wallets.modal.bulk_warning') }}
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
