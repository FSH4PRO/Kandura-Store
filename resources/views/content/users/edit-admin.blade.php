@extends('layouts.contentNavbarLayout')

@section('title', __('admins.edit.title'))

@section('content')
  <div class="row">
    <div class="col-12 mb-4">
      <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
          <h4 class="mb-1">{{ __('admins.edit.heading') }}</h4>
          <p class="mb-0 text-muted">{{ __('admins.edit.subheading') }}</p>
        </div>
        <div>
          <a href="{{ route('admins.index') }}" class="btn btn-outline-secondary btn-sm">
            {{ __('admins.edit.back_to_list') }}
          </a>
        </div>
      </div>
    </div>
  </div>

  {{-- Form --}}
  <div class="row">
    <div class="col-xl-6 col-lg-8 col-md-10">
      <div class="card">
        <div class="card-body">
          <form action="{{ route('admins.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')


            {{-- Roles (Micro-roles on Admin model) --}}
            <div class="mb-4">
              <label class="form-label">{{ __('admins.form.roles') }}</label>

              <select name="roles[]" class="form-select @error('roles') is-invalid @enderror" multiple>
                @php
                  $selectedRoles = collect(old('roles', $currentRoleNames ?? []));
                @endphp

                @foreach ($roles as $roleName)
                  <option value="{{ $roleName }}" {{ $selectedRoles->contains($roleName) ? 'selected' : '' }}>
                    {{ __('admins.roles.' . $roleName) !== 'admins.roles.' . $roleName
                        ? __('admins.roles.' . $roleName)
                        : $roleName }}
                  </option>
                @endforeach
              </select>

              <small class="text-muted d-block mt-1">
                {{ __('admins.form.roles_help') }}
              </small>

              @error('roles')
                <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
            </div>

            <button type="submit" class="btn btn-primary">
              {{ __('admins.edit.submit') }}
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
