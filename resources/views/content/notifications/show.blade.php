@extends('layouts.contentNavbarLayout')

@section('title', __('notifications.show.title'))

@section('content')
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h4 class="mb-1">
                    {{ __('notifications.show.heading') }}
                </h4>
                <p class="mb-0 text-muted">
                    {{ __('notifications.show.subheading') }}
                </p>
            </div>
            <div>
                <a href="{{ route('admin.notifications.index') }}" class="btn btn-outline-secondary btn-sm">
                    {{ __('notifications.show.back_to_list') }}
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Notification details --}}
        <div class="col-lg-8 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('notifications.show.data_title') }}</h5>
                </div>
                <div class="card-body">
                    <pre class="bg-light p-3 rounded">{{ json_encode($notification->data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                </div>
            </div>
        </div>

        {{-- Notification info --}}
        <div class="col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('notifications.show.info_title') }}</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-4">
                        <li class="mb-3 d-flex justify-content-between">
                            <span>{{ __('notifications.show.id') }}</span>
                            <span class="fw-medium">{{ $notification->id }}</span>
                        </li>

                        <li class="mb-3 d-flex justify-content-between">
                            <span>{{ __('notifications.show.type') }}</span>
                            <span class="fw-medium">{{ $notification->type }}</span>
                        </li>

                        <li class="mb-3 d-flex justify-content-between">
                            <span>{{ __('notifications.show.notifiable') }}</span>
                            <span class="fw-medium">{{ $notification->notifiable_type }}
                                #{{ $notification->notifiable_id }}</span>
                        </li>

                        <li class="mb-3 d-flex justify-content-between">
                            <span>{{ __('notifications.show.read_at') }}</span>
                            <span
                                class="fw-medium">{{ $notification->read_at ? $notification->read_at->format('Y-m-d H:i:s') : __('notifications.show.not_read') }}</span>
                        </li>

                        <li class="mb-3 d-flex justify-content-between">
                            <span>{{ __('notifications.show.created_at') }}</span>
                            <span class="fw-medium">{{ $notification->created_at->format('Y-m-d H:i:s') }}</span>
                        </li>

                        <li class="mb-3 d-flex justify-content-between">
                            <span>{{ __('notifications.show.updated_at') }}</span>
                            <span class="fw-medium">{{ $notification->updated_at->format('Y-m-d H:i:s') }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
