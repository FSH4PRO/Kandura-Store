@extends('layouts.contentNavbarLayout')

@section('title', __('admins.create.title'))

@section('content')
  <div class="row">
    <div class="col-12 mb-4">
      <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
          <h4 class="mb-1">{{ __('admins.create.heading') }}</h4>
          <p class="mb-0 text-muted">{{ __('admins.create.subheading') }}</p>
        </div>
        <div>
          <a href="{{ route('admins.index') }}" class="btn btn-outline-secondary btn-sm">
            {{ __('admins.create.back_to_list') }}
          </a>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-xl-6 col-lg-8 col-md-10">
      <div class="card">
        <div class="card-body">
          <form action="{{ route('admins.store') }}" method="POST">
            @csrf

            {{-- Name EN --}}
            <div class="mb-3">
              <label class="form-label">
                {{ __('admins.form.name_en') }} *
              </label>
              <input type="text" name="name[en]" class="form-control @error('name.en') is-invalid @enderror"
                value="{{ old('name.en') }}" placeholder="{{ __('admins.form.name_en_placeholder') }}" required>
              @error('name.en')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            {{-- Name AR --}}
            <div class="mb-3">
              <label class="form-label">
                {{ __('admins.form.name_ar') }}
              </label>
              <input type="text" name="name[ar]" class="form-control @error('name.ar') is-invalid @enderror"
                value="{{ old('name.ar') }}" placeholder="{{ __('admins.form.name_ar_placeholder') }}">
              @error('name.ar')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            {{-- Email --}}
            <div class="mb-3">
              <label class="form-label">
                {{ __('admins.form.email') }} *
              </label>
              <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                value="{{ old('email') }}" placeholder="admin@example.com" required>
              @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            {{-- Password --}}
            <div class="mb-3">
              <label class="form-label">
                {{ __('admins.form.password') }} *
              </label>
              <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                required>
              @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            {{-- Password Confirmation --}}
            <div class="mb-4">
              <label class="form-label">
                {{ __('admins.form.password_confirmation') }} *
              </label>
              <input type="password" name="password_confirmation" class="form-control" required>
            </div>

            {{-- Active status --}}
            <div class="mb-4 form-check">
              <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1"
                {{ old('is_active', 1) ? 'checked' : '' }}>
              <label class="form-check-label" for="is_active">
                {{ __('admins.form.is_active') }}
              </label>
            </div>

            {{-- Roles (Micro-roles) --}}
            <div class="mb-4">
              <label class="form-label">{{ __('admins.form.roles') }}</label>
              <select name="roles[]"
                class="form-select @error('roles') is-invalid @enderror @error('roles.*') is-invalid @enderror" multiple>
                @foreach ($roles as $role)
                  @php
                    $translationKey = 'admins.roles.' . $role;
                    $translated = __($translationKey);
                    $label = $translated !== $translationKey ? $translated : $role;
                  @endphp

                  <option value="{{ $role }}"
                    {{ collect(old('roles', []))->contains($role) ? 'selected' : '' }}>
                    {{ $label }}
                  </option>
                @endforeach
              </select>
              <small class="text-muted d-block mt-1">
                {{ __('admins.form.roles_help') }}
              </small>

              @error('roles')
                <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
              @error('roles.*')
                <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
            </div>

            <button type="submit" class="btn btn-primary">
              {{ __('admins.form.submit') }}
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
