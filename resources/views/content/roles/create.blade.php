@extends('layouts.contentNavbarLayout')

@section('title', __('roles.create.title'))

@section('content')
  <div class="row">
    <div class="col-12 mb-4">
      <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
          <h4 class="mb-1">{{ __('roles.create.heading') }}</h4>
          <p class="mb-0 text-muted">{{ __('roles.create.subheading') }}</p>
        </div>
        <div>
          <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary btn-sm">
            {{ __('roles.create.back_to_list') }}
          </a>
        </div>
      </div>
    </div>
  </div>

  @if ($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div class="row">
    <div class="col-xl-8 col-lg-10">
      <div class="card">
        <div class="card-body">
          <form action="{{ route('roles.store') }}" method="POST">
            @csrf

            {{-- Role name --}}
            <div class="mb-4">
              <label class="form-label">
                {{ __('roles.create.form.name_label') }} *
              </label>
              <input
                type="text"
                name="name"
                class="form-control @error('name') is-invalid @enderror"
                value="{{ old('name') }}"
                placeholder="{{ __('roles.create.form.name_placeholder') }}"
                required
              >
              <small class="text-muted">
                {{ __('roles.create.form.name_help') }}
              </small>
              @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            {{-- Permissions --}}
            <div class="mb-4">
              <label class="form-label d-block">
                {{ __('roles.create.form.permissions_label') }}
              </label>

              @forelse($groupedPermissions as $group => $perms)
                <div class="mb-3 border rounded p-3">
                  <div class="fw-semibold mb-2 text-uppercase small">
                    {{-- نحاول نترجم اسم المجموعة، لو ما في ترجمة نعرض الـ key كما هو --}}
                    @php
                      $groupKey = 'roles.permission_groups.' . $group;
                    @endphp
                    {{ ($groupKey) !== $groupKey ? ($groupKey) : $group }}
                  </div>

                  <div class="row">
                    @foreach ($perms as $perm)
                      @php
                        $permKey = 'roles.permissions.' . $perm->name;
                      @endphp
                      <div class="col-md-4 col-sm-6 mb-2">
                        <div class="form-check">
                          <input
                            class="form-check-input"
                            type="checkbox"
                            name="permissions[]"
                            id="perm_{{ $perm->id }}"
                            value="{{ $perm->name }}"
                            {{ in_array($perm->name, old('permissions', [])) ? 'checked' : '' }}
                          >
                          <label class="form-check-label" for="perm_{{ $perm->id }}">
                            {{ ($permKey) !== $permKey ? ($permKey) : $perm->name }}
                          </label>
                        </div>
                      </div>
                    @endforeach
                  </div>
                </div>
              @empty
                <p class="text-muted">
                  {{ __('roles.create.form.no_permissions') }}
                </p>
              @endforelse

              @error('permissions')
                <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
            </div>

            <button type="submit" class="btn btn-primary">
              {{ __('roles.create.form.submit') }}
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection