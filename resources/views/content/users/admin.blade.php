@extends('layouts.contentNavbarLayout')

@section('title', __('admins.title'))

@section('content')
  <div class="row">
    <div class="col-12 mb-4">
      <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
          <h4 class="mb-1">{{ __('admins.heading') }}</h4>
          <p class="mb-0 text-muted">{{ __('admins.subheading') }}</p>
        </div>
        <div>
          {{-- زر إنشاء أدمن جديد --}}

          @php
            $actor = auth('admin')->user()?->user; // User اللي عليه الرولات
          @endphp

          @if ($actor && $actor->hasRole('super_admin'))
            <a href="{{ route('admins.create') }}" class="btn btn-primary btn-sm">
              {{ __('admins.create_button') }}
            </a>
          @endif


        </div>
      </div>
    </div>
  </div>

  {{-- Filters --}}
  <div class="row mb-4">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <form method="GET" action="{{ route('admins.index') }}" class="row g-3 align-items-end">

            {{-- Search --}}
            <div class="col-md-4">
              <label class="form-label">{{ __('admins.filters.search_label') }}</label>
              <input type="text" name="search" class="form-control"
                placeholder="{{ __('admins.filters.search_placeholder') }}" value="{{ $filters['search'] ?? '' }}">
            </div>

            {{-- Status --}}
            <div class="col-md-3">
              <label class="form-label">{{ __('admins.filters.status_label') }}</label>
              <select name="status" class="form-select">
                <option value="">{{ __('admins.filters.status_all') }}</option>
                <option value="active" {{ ($filters['status'] ?? '') === 'active' ? 'selected' : '' }}>
                  {{ __('admins.filters.status_active') }}
                </option>
                <option value="inactive" {{ ($filters['status'] ?? '') === 'inactive' ? 'selected' : '' }}>
                  {{ __('admins.filters.status_inactive') }}
                </option>
              </select>
            </div>

            {{-- Role --}}
            <div class="col-md-3">
              <label class="form-label">{{ __('admins.filters.role_label') }}</label>
              <select name="role" class="form-select">
                <option value="">{{ __('admins.filters.role_all') }}</option>
                @foreach ($roles as $role)
                  <option value="{{ $role }}" {{ ($filters['role'] ?? '') === $role ? 'selected' : '' }}>
                    {{ __('admins.roles.' . $role) !== 'admins.roles.' . $role ? __('admins.roles.' . $role) : $role }}
                  </option>
                @endforeach
              </select>
            </div>

            {{-- Submit / Reset --}}
            <div class="col-md-2 d-flex gap-2">
              <button type="submit" class="btn btn-primary flex-grow-1">
                {{ __('admins.filters.submit') }}
              </button>
              <a href="{{ route('admins.index') }}" class="btn btn-outline-secondary">
                {{ __('admins.filters.reset') }}
              </a>
            </div>

          </form>
        </div>
      </div>
    </div>
  </div>


  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="table-responsive text-nowrap">
          <table class="table table-hover mb-0">
            <thead>
              <tr>
                <th>{{ __('admins.table.id') }}</th>
                <th>{{ __('admins.table.name') }}</th>
                <th>{{ __('admins.table.email') }}</th>
                <th>{{ __('admins.table.roles') }}</th>
                <th>{{ __('admins.table.status') }}</th>
                <th>{{ __('admins.table.created_at') }}</th>
                <th class="text-center">{{ __('admins.table.actions') }}</th>
              </tr>
            </thead>
            <tbody>
              @forelse($admins as $user)
                @php
                  /** @var \App\Models\User $user */
                  $adminModel = $user->usable; // Admin المرتبط

                  // أسماء الأدوار من Spatie
                  $roleNames = method_exists($user, 'getRoleNames') ? $user->getRoleNames() : collect();

                  // ترجمة أسماء الأدوار لو في keys في admins.roles.*
                  $rolesLabel = $roleNames
                      ->map(function ($role) {
                          $key = 'admins.roles.' . $role;
                          return __($key) !== $key ? __($key) : $role;
                      })
                      ->implode(', ');

                  // السوبر أدمن الحالي (الممثل)
                  $actor = auth('admin')->user()?->user;
                @endphp

                <tr>
                  {{-- ID --}}
                  <td>{{ $user->id }}</td>

                  {{-- Name --}}
                  <td>
                    {{ is_array($user->name) ? $user->name['ar'] ?? ($user->name['en'] ?? '') : $user->name }}
                  </td>

                  {{-- Email --}}
                  <td>{{ $adminModel->email ?? '-' }}</td>

                  {{-- Roles --}}
                  <td>
                    <span class="badge bg-label-info">
                      {{ $rolesLabel ?: __('admins.roles.none') }}
                    </span>
                  </td>

                  {{-- Status --}}
                  <td>
                    @if ($user->is_active)
                      <span class="badge bg-label-success">
                        {{ __('admins.status_badge.active') }}
                      </span>
                    @else
                      <span class="badge bg-label-danger">
                        {{ __('admins.status_badge.inactive') }}
                      </span>
                    @endif
                  </td>

                  {{-- Created at --}}
                  <td>{{ $user->created_at?->format('Y-m-d') }}</td>

                  {{-- Actions --}}
                  <td class="text-center">
                    <div class="dropdown">
                      <button class="btn btn-sm btn-icon btn-text-secondary rounded-pill dropdown-toggle hide-arrow"
                        type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bx bx-dots-vertical-rounded"></i>
                      </button>
                      <div class="dropdown-menu dropdown-menu-end">
                        @if ($actor && $actor->hasRole('super_admin'))
                          {{-- فقط السوبر أدمن يشوف زر الحذف --}}
                          <form action="{{ route('admins.destroy', $user->id) }}" method="POST"
                            onsubmit="return confirm('{{ __('admins.actions.confirm_delete') }}');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="dropdown-item text-danger">
                              {{ __('admins.actions.delete') }}
                            </button>
                          </form>
                        @else
                          <span class="dropdown-item text-muted">
                            {{ __('admins.actions.no_actions') }}
                          </span>
                        @endif
                      </div>
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="7" class="text-center text-muted py-4">
                    {{ __('admins.table.empty') }}
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        @if ($admins instanceof \Illuminate\Contracts\Pagination\Paginator)
          <div class="card-footer">
            {{ $admins->links() }}
          </div>
        @endif
      </div>
    </div>
  </div>
@endsection
