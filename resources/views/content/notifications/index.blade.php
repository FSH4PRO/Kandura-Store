@extends('layouts.contentNavbarLayout')

@section('title', __('notifications.index.title'))

@section('content')
    {{-- Header --}}
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h4 class="mb-1">{{ __('notifications.index.heading') }}</h4>
                <p class="mb-0 text-muted">{{ __('notifications.index.subheading') }}</p>
            </div>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-success" id="mark-all-read">
                    <i class="bx bx-check me-1"></i> {{ __('notifications.mark_all_read') }}
                </button>
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

    {{-- Filters --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.notifications.index') }}" class="row g-3 align-items-end">

                        {{-- Type --}}
                        <div class="col-md-4">
                            <label class="form-label">{{ __('notifications.filters.type_label') }}</label>
                            <input type="text" name="type" class="form-control"
                                placeholder="{{ __('notifications.filters.type_placeholder') }}"
                                value="{{ $filters['type'] ?? '' }}">
                        </div>

                        {{-- Read Status --}}
                        <div class="col-md-4">
                            <label class="form-label">{{ __('notifications.filters.read_label') }}</label>
                            <select name="read" class="form-select">
                                <option value="">{{ __('notifications.filters.read_all') }}</option>
                                <option value="unread" {{ ($filters['read'] ?? '') === 'unread' ? 'selected' : '' }}>
                                    {{ __('notifications.filters.unread') }}
                                </option>
                                <option value="read" {{ ($filters['read'] ?? '') === 'read' ? 'selected' : '' }}>
                                    {{ __('notifications.filters.read') }}
                                </option>
                            </select>
                        </div>

                        <div class="col-md-2 d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-grow-1">
                                {{ __('notifications.filters.submit') }}
                            </button>
                            <a href="{{ route('admin.notifications.index') }}" class="btn btn-outline-secondary">
                                {{ __('notifications.filters.reset') }}
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
                                <th>{{ __('notifications.table.type') }}</th>
                                <th>{{ __('notifications.table.message') }}</th>
                                <th>{{ __('notifications.table.received_at') }}</th>
                                <th class="text-center">{{ __('notifications.table.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($notifications as $notification)
                                <tr class="{{ $notification->read_at ? '' : 'table-warning' }}">
                                    <td>{{ class_basename($notification->type) }}</td>
                                    <td>
                                        <small class="text-muted">
                                            {{ $notification->data['body'] ?? 'No message' }}
                                        </small>
                                    </td>
                                    <td>{{ $notification->created_at->format('Y-m-d H:i') }}
                                    </td>
                                    <td class="text-center">


                                        <div class="d-flex gap-1 justify-content-center">
                                            @if (class_basename($notification->type) == 'AdminOrderCreatedNotification')
                                                @can('orders.view')
                                                    <a href="{{ route('admin.orders.show', $notification->data['data']['order_id'] ?? '#') }}"
                                                        class="btn btn-sm btn-outline-primary">
                                                        {{ __('notifications.table.view_order') }}
                                                    </a>
                                                @endcan
                                            @elseif (class_basename($notification->type) == 'AdminDesignCreatedNotification')
                                                @can('designs.view')
                                                    <a href="{{ route('admin.designs.show', $notification->data['data']['design_id'] ?? '#') }}"
                                                        class="btn btn-sm btn-outline-primary">
                                                        {{ __('notifications.table.view_design') }}
                                                    </a>
                                                @endcan
                                            @else
                                                <a href="{{ route('admin.notifications.show', $notification->id) }}"
                                                    class="btn btn-sm btn-outline-primary">
                                                    {{ __('notifications.table.view') }}
                                                </a>
                                            @endif
                                            @if (!$notification->read_at)
                                                <button type="button" class="btn btn-sm btn-outline-success mark-read-btn"
                                                    data-id="{{ $notification->id }}">
                                                    <i class="bx bx-check"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">
                                        {{ __('notifications.table.empty') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($notifications instanceof \Illuminate\Contracts\Pagination\Paginator)
                    <div class="card-footer">
                        {{ $notifications->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('page-script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mark single notification as read
            document.querySelectorAll('.mark-read-btn').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    const notificationId = this.getAttribute('data-id');
                    const row = this.closest('tr');

                    console.log('Mark read button clicked for ID:', notificationId);

                    fetch(`{{ url('/admin/notifications') }}/${notificationId}/mark-read`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').getAttribute('content'),
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: JSON.stringify({
                                _token: document.querySelector(
                                    'meta[name="csrf-token"]').getAttribute(
                                    'content')
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            console.log('AJAX success:', data);
                            if (data.success) {
                                row.classList.remove('table-warning');
                                btn.remove();
                                console.log('Notification marked as read');
                            } else {
                                console.error(data.message ||
                                    'Failed to mark notification as read');
                            }
                        })
                        .catch(error => {
                            console.error('AJAX error:', error);
                        });
                });
            });

            // Mark all notifications as read
            const markAllBtn = document.getElementById('mark-all-read');
            if (markAllBtn) {
                markAllBtn.addEventListener('click', function() {
                    console.log('Mark all read button clicked');

                    fetch('{{ route('admin.notifications.mark-all-read') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content'),
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: JSON.stringify({
                                _token: document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content')
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            console.log('AJAX success:', data);
                            if (data.success) {
                                document.querySelectorAll('.table-warning').forEach(row => row.classList
                                    .remove('table-warning'));
                                document.querySelectorAll('.mark-read-btn').forEach(btn => btn
                                    .remove());
                                console.log('All notifications marked as read');
                            } else {
                                console.error(data.message ||
                                    'Failed to mark all notifications as read');
                            }
                        })
                        .catch(error => {
                            console.error('AJAX error:', error);
                        });
                });
            }
        });
    </script>
@endpush
