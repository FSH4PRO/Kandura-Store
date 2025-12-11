@extends('layouts.contentNavbarLayout')

@section('title', __('designs.show.title'))

@php
  /** @var \App\Models\Design $design */
  $customer = $design->customer;
  $user = $customer?->user;
  $mainImageUrl = $design->first_image_url ?? $design->getFirstMediaUrl('images');
  $images = $design->getMedia('images');
@endphp

@section('content')
  <div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center flex-wrap gap-3">
      <div>
        <h4 class="mb-1">{{ __('designs.show.heading') }}</h4>
        <p class="mb-0 text-muted">{{ __('designs.show.subheading') }}</p>
      </div>

      <div class="d-flex gap-2">
        <a href="{{ route('admin.designs.index') }}" class="btn btn-outline-secondary">
          <i class="bx bx-arrow-back me-1"></i>
          {{ __('designs.show.back_to_list') }}
        </a>
      </div>
    </div>
  </div>

  <div class="row gy-4">
    {{-- Left: main design card --}}
    <div class="col-lg-8">
      <div class="card mb-4">
        @if ($mainImageUrl)
          <div class="card-img-top text-center bg-light" style="max-height: 380px; overflow: hidden;">
            <img src="{{ $mainImageUrl }}" alt="{{ $design->name }}" class="img-fluid"
              style="object-fit: cover; max-height: 380px;">
          </div>
        @endif

        <div class="card-body">
          <div class="d-flex justify-content-between flex-wrap gap-2 mb-3">
            <div>
              <h4 class="card-title mb-1">
                {{ is_array($design->name) ? $design->name[app()->getLocale()] ?? ($design->name['en'] ?? '') : $design->name }}
              </h4>
              <span class="badge bg-label-primary">
                {{ number_format($design->price, 2) }} {{ __('designs.show.currency') }}
              </span>
            </div>

            <div class="text-muted text-end">
              <div>
                <i class="bx bx-time-five me-1"></i>
                {{ __('designs.show.created_at') }}:
                {{ $design->created_at?->format('Y-m-d H:i') }}
              </div>
              @if ($design->updated_at && $design->updated_at->ne($design->created_at))
                <div class="small">
                  <i class="bx bx-refresh me-1"></i>
                  {{ __('designs.show.updated_at') }}:
                  {{ $design->updated_at?->format('Y-m-d H:i') }}
                </div>
              @endif
            </div>
          </div>

          {{-- Description --}}
          @if (!empty($design->description))
            <h6 class="text-muted mb-2">{{ __('designs.show.description') }}</h6>
            <p class="mb-4">
              {{ is_array($design->description)
                  ? $design->description[app()->getLocale()] ?? ($design->description['en'] ?? '')
                  : $design->description }}
            </p>
          @endif

          <div class="row gy-3">
            {{-- Sizes --}}
            <div class="col-md-6">
              <h6 class="text-muted mb-2">
                <i class="bx bx-ruler me-1"></i>{{ __('designs.show.sizes') }}
              </h6>
              @forelse ($design->sizes as $size)
                <span class="badge bg-label-info me-1 mb-1">
                  {{ $size->code }}
                </span>
              @empty
                <span class="text-muted small">{{ __('designs.show.no_sizes') }}</span>
              @endforelse
            </div>

            {{-- Options --}}
            <div class="col-md-6">
              <h6 class="text-muted mb-2">
                <i class="bx bx-slider-alt me-1"></i>{{ __('designs.show.options') }}
              </h6>
              @forelse ($design->options as $option)
                <div class="d-flex align-items-center mb-1">
                  <span class="badge bg-label-secondary me-2 text-uppercase">
                    {{ $option->type }}
                  </span>
                  <span>
                    {{ is_array($option->name) ? $option->name[app()->getLocale()] ?? ($option->name['en'] ?? '') : $option->name }}
                  </span>
                </div>
              @empty
                <span class="text-muted small">{{ __('designs.show.no_options') }}</span>
              @endforelse
            </div>
          </div>
        </div>
      </div>

      {{-- Gallery --}}
      <div class="card">
        <div class="card-header">
          <h5 class="mb-0">
            <i class="bx bx-images me-1"></i>{{ __('designs.show.gallery') }}
          </h5>
        </div>
        <div class="card-body">
          @if ($images->isEmpty())
            <p class="text-muted mb-0">{{ __('designs.show.no_images') }}</p>
          @else
            <div class="row g-3">
              @foreach ($images as $media)
                <div class="col-6 col-md-4 col-lg-3">
                  <a href="{{ $media->getUrl() }}" target="_blank" class="d-block">
                    <div class="ratio ratio-1x1 rounded border overflow-hidden bg-light">
                      <img src="{{ $media->getUrl('thumb') }}" class="w-100 h-100" style="object-fit: cover;"
                        alt="design image">
                    </div>
                  </a>
                </div>
              @endforeach
            </div>
          @endif
        </div>
      </div>
    </div>

    {{-- Right: customer info --}}
    <div class="col-lg-4">
      <div class="card mb-4">
        <div class="card-header">
          <h5 class="mb-0">
            <i class="bx bx-user me-1"></i>{{ __('designs.show.customer_info.title') }}
          </h5>
        </div>
        <div class="card-body">
          @if ($customer && $user)
            <div class="d-flex align-items-center mb-3">
              <div class="avatar flex-shrink-0 me-3">
                <img src="{{ $user->avatar_url ?? asset('assets/img/avatars/1.png') }}" alt="avatar"
                  class="rounded-circle w-px-40 h-px-40">
              </div>
              <div>
                <h6 class="mb-0">
                  {{ is_array($user->name) ? $user->name[app()->getLocale()] ?? ($user->name['en'] ?? '') : $user->name }}
                </h6>
                <small class="text-muted">
                  {{ __('designs.show.customer_info.type_customer') }}
                </small>
              </div>
            </div>

            <ul class="list-unstyled mb-0">
              <li class="mb-2">
                <i class="bx bx-id-card me-2"></i>
                <span class="text-muted">{{ __('designs.show.customer_info.customer_id') }}:</span>
                <span class="fw-medium">{{ $customer->id }}</span>
              </li>
              <li class="mb-2">
                <i class="bx bx-calendar me-2"></i>
                <span class="text-muted">{{ __('designs.show.customer_info.joined_at') }}:</span>
                <span class="fw-medium">{{ $customer->created_at?->format('Y-m-d') }}</span>
              </li>
            </ul>
          @else
            <p class="text-muted mb-0">
              {{ __('designs.show.customer_info.not_available') }}
            </p>
          @endif
        </div>
      </div>

      {{-- Meta info --}}
      <div class="card">
        <div class="card-header">
          <h5 class="mb-0">
            <i class="bx bx-info-circle me-1"></i>{{ __('designs.show.meta.title') }}
          </h5>
        </div>
        <div class="card-body">
          <ul class="list-unstyled mb-0">
            <li class="mb-2">
              <span class="text-muted">{{ __('designs.show.meta.design_id') }}:</span>
              <span class="fw-medium">{{ $design->id }}</span>
            </li>
            <li class="mb-2">
              <span class="text-muted">{{ __('designs.show.meta.price') }}:</span>
              <span class="fw-medium">
                {{ number_format($design->price, 2) }} {{ __('designs.show.currency') }}
              </span>
            </li>
            <li class="mb-2">
              <span class="text-muted">{{ __('designs.show.meta.sizes_count') }}:</span>
              <span class="fw-medium">{{ $design->sizes->count() }}</span>
            </li>
            <li class="mb-0">
              <span class="text-muted">{{ __('designs.show.meta.options_count') }}:</span>
              <span class="fw-medium">{{ $design->options->count() }}</span>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
@endsection
