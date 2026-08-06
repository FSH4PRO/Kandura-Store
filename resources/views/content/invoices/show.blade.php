@extends('layouts.contentNavbarLayout')

@section('title', __('invoices.show.title', ['number' => $invoice->invoice_number]))

@section('content')
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h4 class="mb-1">
                    {{ __('invoices.show.heading', ['number' => $invoice->invoice_number]) }}
                </h4>
                <p class="mb-0 text-muted">
                    {{ __('invoices.show.subheading') }}
                </p>
            </div>
            <div>
                <a href="{{ route('admin.invoices.index') }}" class="btn btn-outline-secondary btn-sm">
                    {{ __('invoices.show.back_to_list') }}
                </a>
                @if ($invoice->pdf_url)
                    <a href="{{ $invoice->pdf_url }}" target="_blank" class="btn btn-primary btn-sm ms-2">
                        {{ __('invoices.show.download_pdf') }}
                    </a>
                @endif
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Invoice summary --}}
        <div class="col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('invoices.show.summary_title') }}</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-4">
                        <li class="mb-3 d-flex justify-content-between">
                            <span>{{ __('invoices.show.invoice_number') }}</span>
                            <span class="fw-medium">{{ $invoice->invoice_number }}</span>
                        </li>

                        <li class="mb-3 d-flex justify-content-between">
                            <span>{{ __('invoices.show.order_id') }}</span>
                            <span class="fw-medium">#{{ $invoice->order->id }}</span>
                        </li>

                        <li class="mb-3 d-flex justify-content-between">
                            <span>{{ __('invoices.show.customer') }}</span>
                            <span class="fw-medium">{{ $invoice->order->customer->user->name ?? '-' }}</span>
                        </li>

                        <li class="mb-3 d-flex justify-content-between">
                            <span>{{ __('invoices.show.total') }}</span>
                            <span class="fw-medium">{{ number_format($invoice->total, 2) }}</span>
                        </li>

                        <li class="mb-3 d-flex justify-content-between">
                            <span>{{ __('invoices.show.created_at') }}</span>
                            <span class="fw-medium">{{ $invoice->created_at->format('Y-m-d H:i') }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Order items --}}
        <div class="col-lg-8 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('invoices.show.items_title') }}</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Design</th>
                                    <th>Size</th>
                                    <th>Options</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($invoice->order->items as $item)
                                    <tr>
                                        <td>{{ $item->design->name }}</td>
                                        <td>{{ $item->size->name ?? '-' }}</td>
                                        <td>
                                            @if ($item->options->count() > 0)
                                                @foreach ($item->options as $option)
                                                    {{ $option->option->name }}: {{ $option->value }}<br>
                                                @endforeach
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>{{ number_format($item->price, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
