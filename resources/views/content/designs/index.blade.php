@extends('layouts.contentNavbarLayout')

@section('title', __('admin_designs.index.title'))

@section('content')
  <div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center flex-wrap gap-3">
      <div>
        <h4 class="mb-1">{{ __('designs.heading') }}</h4>
        <p class="mb-0 text-muted">{{ __('designs.subheading') }}</p>
      </div>
    </div>
  </div>

  {{-- Filters --}}
  <div class="row mb-4">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <form method="GET" action="{{ route('admin.designs.index') }}" class="row g-3 align-items-end">

            {{-- Search --}}
            <div class="col-md-4">
              <label class="form-label">{{ __('designs.filters.search_label') }}</label>
              <input type="text" name="search" class="form-control"
                placeholder="{{ __('designs.filters.search_placeholder') }}" value="{{ $filters['search'] ?? '' }}">
            </div>

            {{-- Size --}}
            <div class="col-md-3">
              <label class="form-label">{{ __('designs.filters.size_label') }}</label>
              <select name="size_id" class="form-select">
                <option value="">{{ __('designs.filters.size_all') }}</option>
                @foreach ($sizes as $size)
                  <option value="{{ $size->id }}" {{ ($filters['size_id'] ?? '') == $size->id ? 'selected' : '' }}>
                    {{ $size->code }} -
                    @php
                      $sizeName = $size->name;
                      if (is_array($sizeName)) {
                          $locale = app()->getLocale();
                          $sizeName = $sizeName[$locale] ?? ($sizeName['en'] ?? reset($sizeName));
                      }
                    @endphp
                    {{ $sizeName }}
                  </option>
                @endforeach
              </select>
            </div>

            {{-- Price min --}}
            <div class="col-md-2">
              <label class="form-label">{{ __('designs.filters.price_min') }}</label>
              <input type="number" step="0.01" name="price_min" class="form-control"
                value="{{ $filters['price_min'] ?? '' }}">
            </div>

            {{-- Price max --}}
            <div class="col-md-2">
              <label class="form-label">{{ __('designs.filters.price_max') }}</label>
              <input type="number" step="0.01" name="price_max" class="form-control"
                value="{{ $filters['price_max'] ?? '' }}">
            </div>

            {{-- Option --}}
            <div class="col-md-3">
              <label class="form-label">{{ __('designs.filters.option_label') }}</label>
              <select name="option_id" class="form-select">
                <option value="">{{ __('designs.filters.option_all') }}</option>
                @foreach ($options as $option)
                  @php
                    $optName = $option->name;
                    if (is_array($optName)) {
                        $locale = app()->getLocale();
                        $optName = $optName[$locale] ?? ($optName['en'] ?? reset($optName));
                    }
                  @endphp
                  <option value="{{ $option->id }}"
                    {{ ($filters['option_id'] ?? '') == $option->id ? 'selected' : '' }}>
                    {{ $optName }}
                  </option>
                @endforeach
              </select>
            </div>

            {{-- Actions --}}
            <div class="col-md-3 d-flex gap-2">
              <button type="submit" class="btn btn-primary flex-grow-1">
                {{ __('designs.filters.submit') }}
              </button>
              <a href="{{ route('admin.designs.index') }}" class="btn btn-outline-secondary">
                {{ __('designs.filters.reset') }}
              </a>
            </div>

          </form>
        </div>
      </div>
    </div>
  </div>

  {{-- Cards Grid --}}
  <div class="row g-4">
    @forelse ($designs as $design)
      @php
        $customer = $design->customer;
        $user = $customer?->user;

        // اسم التصميم (ترجمة)
        $name = $design->name;
        if (is_array($name)) {
            $locale = app()->getLocale();
            $name = $name[$locale] ?? ($name['en'] ?? reset($name));
        }

        // اسم العميل (من user->name)
        $customerName = $user?->name;
        if (is_array($customerName ?? null)) {
            $locale = app()->getLocale();
            $customerName = $customerName[$locale] ?? ($customerName['en'] ?? reset($customerName));
        }

        $imageUrl =
            $design->first_image_url ??
            ($design->getFirstMediaUrl('images', 'thumb') ?? asset('assets/img/illustrations/placeholder.png'));
      @endphp

      <div class="col-md-6 col-xl-4">
        <div class="card h-100">

          {{-- Image --}}
          <div class="card-img-top bg-light position-relative" style="aspect-ratio: 4 / 3; overflow: hidden;">
            @if ($design->getMedia('images')->isNotEmpty())
              <img src="{{ $imageUrl }}" alt="{{ $name }}" class="w-100 h-100" style="object-fit: cover;">
            @else
              <div class="d-flex align-items-center justify-content-center h-100 text-muted">
                {{ __('designs.card.no_image') }}
              </div>
            @endif

            {{-- Price badge --}}
            <span class="badge bg-label-primary position-absolute top-0 end-0 m-3">
              {{ number_format($design->price, 2) }}
            </span>
          </div>

          {{-- Body --}}
          <div class="card-body">
            <h5 class="card-title mb-1">{{ $name }}</h5>

            {{-- Customer --}}
            <p class="mb-2 text-muted">
              <i class="bx bx-user me-1"></i>
              {{ $customerName ?? __('designs.card.unknown_customer') }}
            </p>

            {{-- Sizes --}}
            <div class="mb-2">
              @foreach ($design->sizes as $s)
                <span class="badge bg-label-info me-1 mb-1">{{ $s->code }}</span>
              @endforeach
            </div>

            {{-- Created At --}}
            <small class="text-muted">
              <i class="bx bx-calendar me-1"></i>
              {{ $design->created_at?->format('Y-m-d') }}
            </small>
          </div>

          {{-- Footer actions --}}
          <div class="card-footer d-flex justify-content-between align-items-center">
            <a href="{{ route('admin.designs.show', $design->id) }}" class="btn btn-sm btn-outline-primary">
              {{ __('designs.table.view') }}
            </a>

            <small class="text-muted">
              {{ trans_choice('designs.card.options_count', $design->options->count(), ['count' => $design->options->count()]) }}
            </small>
          </div>
        </div>
      </div>
    @empty
      <div class="col-12">
        <div class="card">
          <div class="card-body text-center text-muted py-4">
            {{ __('designs.table.empty') }}
          </div>
        </div>
      </div>
    @endforelse
  </div>

  {{-- Pagination --}}
  @if ($designs instanceof \Illuminate\Contracts\Pagination\Paginator)
    <div class="row mt-4">
      <div class="col-12 d-flex justify-content-center">
        {{ $designs->links() }}
      </div>
    </div>
  @endif
@endsection
