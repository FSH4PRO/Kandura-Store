@extends('layouts.contentNavbarLayout')

@section('title', __('design_options.create.title'))

@section('content')

  <div class="row">
    <div class="col-12 mb-4">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <h4>{{ __('design_options.create.heading') }}</h4>
          <p class="text-muted">{{ __('design_options.create.subheading') }}</p>
        </div>
        <a href="{{ route('admin.design-options.index') }}" class="btn btn-outline-secondary btn-sm">
          {{ __('design_options.create.back') }}
        </a>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-xl-6 col-lg-8 col-md-10">
      <div class="card">
        <div class="card-body">

          <form action="{{ route('admin.design-options.store') }}" method="POST">
            @csrf

            {{-- Name EN --}}
            <div class="mb-3">
              <label class="form-label">{{ __('design_options.form.name_en') }} *</label>
              <input type="text" name="name[en]" class="form-control @error('name.en') is-invalid @enderror"
                value="{{ old('name.en') }}">
              @error('name.en')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            {{-- Name AR --}}
            <div class="mb-3">
              <label class="form-label">{{ __('design_options.form.name_ar') }}</label>
              <input type="text" name="name[ar]" class="form-control @error('name.ar') is-invalid @enderror"
                value="{{ old('name.ar') }}">
              @error('name.ar')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            {{-- Type --}}
            <div class="mb-3">
              <label class="form-label">{{ __('design_options.form.type') }} *</label>
              <select name="type" class="form-select @error('type') is-invalid @enderror">
                <option value="">{{ __('design_options.form.choose_type') }}</option>
                @foreach ($types as $type)
                  <option value="{{ $type }}" {{ old('type') === $type ? 'selected' : '' }}>
                    {{ __('design_options.types.' . $type) }}
                  </option>
                @endforeach
              </select>
              @error('type')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            {{-- Active --}}
            <div class="form-check mb-4">
              <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1"
                {{ old('is_active', 1) ? 'checked' : '' }}>
              <label class="form-check-label" for="is_active">
                {{ __('design_options.form.status') }}
              </label>
            </div>

            <button type="submit" class="btn btn-primary">
              {{ __('design_options.create.submit') }}
            </button>

          </form>

        </div>
      </div>w
    </div>
  </div>

@endsection
