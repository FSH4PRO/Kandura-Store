@extends('layouts.contentNavbarLayout')

@section('title', __('orders.show.title', ['id' => $order->id]))

@section('content')
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h4 class="mb-1">
                    {{ __('orders.show.heading', ['id' => $order->id]) }}
                </h4>
                <p class="mb-0 text-muted">
                    {{ __('orders.show.subheading') }}
                </p>
            </div>
            <div>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary btn-sm">
                    {{ __('orders.show.back_to_list') }}
                </a>
            </div>
        </div>
    </div>

    {{-- messages --}}

    @if (session('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        {{-- Order summary --}}
        <div class="col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('orders.show.summary_title') }}</h5>
                </div>
                <div class="card-body">
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

                    <ul class="list-unstyled mb-4">
                        <li class="mb-3 d-flex justify-content-between">
                            <span>{{ __('orders.show.serial_number') }}</span>
                            <span class="fw-medium">{{ $order->serial_number }}</span>
                        </li>

                        <li class="mb-3 d-flex justify-content-between">
                            <span>{{ __('orders.show.customer') }}</span>
                            <span class="fw-medium">{{ $user?->name ?? '-' }}</span>
                        </li>

                        <li class="mb-3 d-flex justify-content-between">
                            <span>{{ __('orders.show.subtotal') }}</span>
                            <span class="fw-medium">
                                {{ number_format($order->subtotal, 2) }}
                                @if (!empty($order->currency))
                                    <small class="text-muted">{{ $order->currency }}</small>
                                @endif
                            </span>
                        </li>

                        @if ($order->discount_total > 0)
                            <li class="mb-3 d-flex justify-content-between">
                                <span>{{ __('orders.show.discount') }}</span>
                                <span class="fw-medium text-danger">
                                    -{{ number_format($order->discount_total, 2) }}
                                    @if (!empty($order->currency))
                                        <small class="text-muted">{{ $order->currency }}</small>
                                    @endif
                                </span>
                            </li>
                        @endif

                        @if ($order->coupon)
                            <li class="mb-3 d-flex justify-content-between">
                                <span>{{ __('orders.show.coupon') }}</span>
                                <span class="fw-medium">{{ $order->coupon->code }}</span>
                            </li>
                        @endif

                        <li class="mb-3 d-flex justify-content-between">
                            <span>{{ __('orders.show.total') }}</span>
                            <span class="fw-medium">
                                {{ number_format($order->total, 2) }}
                                @if (!empty($order->currency))
                                    <small class="text-muted">{{ $order->currency }}</small>
                                @endif
                            </span>
                        </li>

                        <li class="mb-3 d-flex justify-content-between align-items-center">
                            <span>{{ __('orders.show.status') }}</span>
                            <span class="fw-medium">
                                <span class="badge {{ $statusClass }}">
                                    {{ __('orders.statuses.' . $statusValue) }}
                                </span>
                            </span>
                        </li>

                        <li class="mb-1 d-flex justify-content-between">
                            <span>{{ __('orders.show.created_at') }}</span>
                            <span class="fw-medium">{{ $order->created_at?->format('Y-m-d H:i') }}</span>
                        </li>
                    </ul>

                    {{-- Change status form --}}
                    @can('orders.change_status')
                        <hr class="my-4">

                        <h6 class="mb-3">{{ __('orders.show.change_status_title') }}</h6>

                        <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label class="form-label">{{ __('orders.show.status_field') }}</label>
                                <select name="status" class="form-select @error('status') is-invalid @enderror">
                                    @foreach ($statusOptions as $status)
                                        <option value="{{ $status->value }}"
                                            {{ $statusValue === $status->value ? 'selected' : '' }}>
                                            {{ __('orders.statuses.' . $status->value) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary">
                                {{ __('orders.show.change_status_button') }}
                            </button>
                        </form>
                    @endcan
                </div>
            </div>
        </div>
        

        {{-- Order items --}}
        <div class="col-lg-8 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('orders.show.items_title') }}</h5>
                </div>
                <div class="card-body">
                    @if ($order->items->isEmpty())
                        <p class="text-muted mb-0">
                            {{ __('orders.show.items_empty') }}
                        </p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>{{ __('orders.items.design') }}</th>
                                        <th>{{ __('orders.items.size') }}</th>
                                        <th>{{ __('orders.items.quantity') }}</th>
                                        <th>{{ __('orders.items.unit_price') }}</th>
                                        <th>{{ __('orders.items.total_price') }}</th>
                                        <th>{{ __('orders.items.options') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($order->items as $item)
                                        <tr>
                                            <td>{{ $item->design?->name ?? '-' }}</td>
                                            <td>{{ $item->size?->code ?? '-' }}</td>
                                            <td>{{ $item->quantity }}</td>
                                            <td>{{ number_format($item->unit_price, 2) }}</td>
                                            <td>{{ number_format($item->line_total, 2) }}</td>
                                            <td>
                                                @if ($item->options->isEmpty())
                                                    <span class="text-muted">-</span>
                                                @else
                                                    @foreach ($item->options as $optSel)
                                                        <span class="badge bg-label-secondary mb-1">
                                                            {{ $optSel->option?->name }}:
                                                            @if (is_array($optSel->value))
                                                                {{ implode(', ', $optSel->value) }}
                                                            @else
                                                                {{ $optSel->value }}
                                                            @endif
                                                        </span><br>
                                                    @endforeach
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        {{-- Review Card --}}
        <div class="col-lg-12 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ __('orders.review.title') }}</h5>

                    @if ($order->review)
                        @php
                            $rating = (int) $order->review->rating;
                            $modalId = 'reviewModalOrderShow' . $order->id;
                        @endphp

                        <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal"
                            data-bs-target="#{{ $modalId }}">
                            {{ __('orders.review.view') }}
                        </button>
                    @endif
                </div>

                <div class="card-body">
                    @if (!$order->review)
                        <div class="text-center py-4">
                            <i class="bx bx-star bx-lg text-muted mb-2"></i>
                            <p class="text-muted mb-0">{{ __('orders.review.no_review') }}</p>
                        </div>
                    @else
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="text-warning fs-5">
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= $rating)
                                        ★
                                    @else
                                        <span class="text-muted">★</span>
                                    @endif
                                @endfor
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="text-muted mb-1">{{ __('orders.review.comment') }}</div>
                            <div class="border rounded p-2 bg-light">
                                {{ $order->review->comment ?: __('orders.review.no_comment') }}
                            </div>
                        </div>

                        <div class="text-muted small">
                            {{ __('orders.review.created_at') }}:
                            {{ $order->review->created_at?->format('Y-m-d H:i') }}
                        </div>

                        {{-- Modal --}}
                        <div class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-sm">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">{{ __('orders.review.details') }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>

                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <div class="d-flex justify-content-between">
                                                <span class="text-muted">{{ __('orders.review.order') }}</span>
                                                <span class="fw-semibold">#{{ $order->serial_number ?? $order->id }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between mt-2">
                                                <span class="text-muted">{{ __('orders.review.customer') }}</span>
                                                <span class="fw-semibold">{{ $user?->name ?? '-' }}</span>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <div class="text-muted mb-1">{{ __('orders.review.rating') }}</div>
                                            <div class="fs-5 text-warning">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    @if ($i <= $rating)
                                                        ★
                                                    @else
                                                        <span class="text-muted">★</span>
                                                    @endif
                                                @endfor
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <div class="text-muted mb-1">{{ __('orders.review.comment') }}</div>
                                            <div class="border rounded p-2 bg-light">
                                                {{ $order->review->comment ?: __('orders.review.no_comment') }}
                                            </div>
                                        </div>

                                        <div class="text-muted small">
                                            {{ __('orders.review.created_at') }}:
                                            {{ $order->review->created_at?->format('Y-m-d H:i') }}
                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-secondary btn-sm"
                                            data-bs-dismiss="modal">
                                            {{ __('common.close') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
