@extends('layouts.contentNavbarLayout')

@section('title', __('invoices.index.title'))

@section('content')
    {{-- Header --}}
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h4 class="mb-1">{{ __('invoices.index.heading') }}</h4>
                <p class="mb-0 text-muted">{{ __('invoices.index.subheading') }}</p>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.invoices.index') }}" class="row g-3 align-items-end">

                        {{-- Search --}}
                        <div class="col-md-6">
                            <label class="form-label">{{ __('invoices.filters.search_label') }}</label>
                            <input type="text" name="search" class="form-control"
                                placeholder="{{ __('invoices.filters.search_placeholder') }}"
                                value="{{ $filters['search'] ?? '' }}">
                        </div>

                        <div class="col-md-3 d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-grow-1">
                                {{ __('invoices.filters.submit') }}
                            </button>
                            <a href="{{ route('admin.invoices.index') }}" class="btn btn-outline-secondary">
                                {{ __('invoices.filters.reset') }}
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
                                <th>{{ __('invoices.table.invoice_number') }}</th>
                                <th>{{ __('invoices.table.customer') }}</th>
                                <th>{{ __('invoices.table.total') }}</th>
                                <th>{{ __('invoices.table.serial_number') }}</th>
                                <th>{{ __('invoices.table.created_at') }}</th>
                                <th class="text-center">{{ __('invoices.table.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($invoices as $invoice)
                                <tr>
                                    <td>{{ $invoice->invoice_number }}</td>
                                    <td>{{ $invoice->order->customer->user->name ?? '-' }}</td>
                                    <td>{{ number_format($invoice->total, 2) }}</td>
                                    <td>{{ $invoice->order->serial_number }}</td>
                                    <td>{{ $invoice->created_at->format('Y-m-d H:i') }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.invoices.show', $invoice->id) }}"
                                            class="btn btn-sm btn-outline-primary">
                                            {{ __('invoices.table.view') }}
                                        </a>
                                        @if ($invoice->pdf_url)
                                            <a href="{{ $invoice->pdf_url }}" target="_blank"
                                                class="btn btn-sm btn-outline-secondary ms-1">
                                                {{ __('invoices.table.pdf') }}
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        {{ __('invoices.table.empty') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($invoices instanceof \Illuminate\Contracts\Pagination\Paginator)
                    <div class="card-footer">
                        {{ $invoices->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
