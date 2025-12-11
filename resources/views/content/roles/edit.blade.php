@extends('layouts.contentNavbarLayout')

@section('title', __('roles.edit.title'))

@section('content')
  <div class="row">
    <div class="col-12 mb-4">
      <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
          <h4 class="mb-1">{{ __('roles.edit.heading') }}</h4>
          <p class="mb-0 text-muted">{{ __('roles.edit.subheading') }}</p>
        </div>
        <div>
          <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary btn-sm">
            {{ __('roles.edit.back_to_list') }}
          </a>
        </div>
      </div>
    </div>
  </div>

  @php
    // أسماء الصلاحيات المرتبطة بالدور الحالي
    $rolePermissionNames = $role->permissions->pluck('name')->toArray();
  @endphp

  <div class="row">
    <div class="col-xl-6 col-lg-8 col-md-10">
      <div class="card">
        <div class="card-body">
          <form action="{{ route('roles.update', $role) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Role name --}}
            <div class="mb-3">
              <label class="form-label">
                {{ __('roles.edit.form.name_label') }}
              </label>
              <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                value="{{ old('name', $role->name) }}" placeholder="{{ __('roles.edit.form.name_placeholder') }}"
                required>
              @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            {{-- Permissions (checkboxes) --}}
            <div class="mb-4">
              <label class="form-label d-block">
                {{ __('roles.edit.form.permissions') }}
              </label>

              <small class="text-muted d-block mb-2">
                {{ __('roles.edit.form.permissions_help') }}
              </small>

              @forelse ($groupedPermissions as $group => $permissions)
                {{-- Group Title --}}
                <div class="mb-2">
                  <strong class="text-primary">
                    {{ __('roles.permission_groups.' . $group) !== 'roles.permission_groups.' . $group
                        ? __('roles.permission_groups.' . $group)
                        : ucfirst($group) }}
                  </strong>
                </div>

                <div class="row mb-3">
                  @foreach ($permissions as $permission)
                    <div class="col-md-6 mb-2">
                      <div class="form-check">

                        <input class="form-check-input" type="checkbox" name="permissions[]"
                          id="perm_{{ $permission->id }}" value="{{ $permission->name }}"
                          {{ in_array($permission->name, old('permissions', $rolePermissionNames)) ? 'checked' : '' }}>

                        <label class="form-check-label" for="perm_{{ $permission->id }}">
                          {{ __('roles.permissions.' . $permission->name) !== 'roles.permissions.' . $permission->name
                              ? __('roles.permissions.' . $permission->name)
                              : $permission->name }}
                        </label>

                      </div>
                    </div>
                  @endforeach
                </div>

                <hr>
              @empty
                <p class="text-muted">{{ __('roles.edit.no_permissions') }}</p>
              @endforelse

              @error('permissions')
                <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
            </div>

            <button type="submit" class="btn btn-primary">
              {{ __('roles.edit.form.submit') }}
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
