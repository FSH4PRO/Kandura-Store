@extends('layouts.contentNavbarLayout')

@section('title', __('users.title'))

@section('content')
  <div class="row">
    <div class="col-12 mb-4">
      <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
          <h4 class="mb-1">{{ __('users.heading') }}</h4>
          <p class="mb-0 text-muted">{{ __('users.subheading') }}</p>
        </div>

      </div>
    </div>
  </div>

  {{-- Filters --}}
  <div class="row mb-4">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <form method="GET" action="{{ route('users.index') }}" class="row g-3 align-items-end">

            {{-- Search --}}
            <div class="col-md-4">
              <label class="form-label">{{ __('users.filters.search_label') }}</label>
              <input type="text" name="search" class="form-control"
                placeholder="{{ __('users.filters.search_placeholder') }}" value="{{ $filters['search'] ?? '' }}">
            </div>

            {{-- Status --}}
            <div class="col-md-3">
              <label class="form-label">{{ __('users.filters.status_label') }}</label>
              <select name="status" class="form-select">
                <option value="">{{ __('users.filters.status_all') }}</option>
                <option value="active" {{ ($filters['status'] ?? '') === 'active' ? 'selected' : '' }}>
                  {{ __('users.filters.status_active') }}
                </option>
                <option value="inactive" {{ ($filters['status'] ?? '') === 'inactive' ? 'selected' : '' }}>
                  {{ __('users.filters.status_inactive') }}
                </option>
              </select>
            </div>

            

            {{-- Submit --}}
            <div class="col-md-2 d-flex gap-2">
              <button type="submit" class="btn btn-primary flex-grow-1">
                {{ __('users.filters.submit') }}
              </button>
              <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                {{ __('users.filters.reset') }}
              </a>
            </div>

          </form>
        </div>
      </div>
    </div>
  </div>

  {{-- Users Table --}}
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="table-responsive text-nowrap">
          <table class="table table-hover mb-0">
            <thead>
              <tr>
                <th>{{ __('users.table.id') }}</th>
                <th>{{ __('users.table.name') }}</th>
                <th>{{ __('users.table.phone') }}</th>
                <th>{{ __('users.table.status') }}</th>
                <th>{{ __('users.table.created_at') }}</th>
                <th class="text-center">{{ __('users.table.actions') }}</th>
              </tr>
            </thead>
            <tbody>
              @forelse($users as $user)
                @php
                  // Admin أو Customer أو شيء آخر
                  $owner = $user->usable;
                  $phone = $owner->phone ?? null;

                  // أسماء الأدوار من Spatie
                  $roleNames = method_exists($user, 'getRoleNames') ? $user->getRoleNames() : collect();

                  // تحويل الأدوار لنصوص مترجمة
                  $rolesLabel = $roleNames
                      ->map(function ($role) {
                          $key = 'users.roles.' . $role;
                          return __($key) !== $key ? __($key) : $role;
                      })
                      ->implode(', ');
                @endphp

                <tr>
                  {{-- ID --}}
                  <td>{{ $user->id }}</td>

                  {{-- Name (من users.name كـ JSON قابل للترجمة) --}}
                  <td>
                    {{ is_array($user->name) ? $user->name['ar'] ?? ($user->name['en'] ?? '') : $user->name }}
                  </td>

                  {{-- Phone من Admin/Customer --}}
                  <td>{{ $phone ?? '-' }}</td>

                 
                  {{-- Status --}}
                  <td>
                    @if ($user->is_active)
                      <span class="badge bg-label-success">{{ __('users.status_badge.active') }}</span>
                    @else
                      <span class="badge bg-label-danger">{{ __('users.status_badge.inactive') }}</span>
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
                      

                        @can('users.delete')
                          <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                            onsubmit="return confirm('{{ __('users.actions.confirm_delete') }}');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="dropdown-item text-danger">
                              {{ __('users.actions.delete') }}
                            </button>
                          </form>
                        @endcan
                      </div>
                    </div>
                  </td>

                </tr>
              @empty
                <tr>
                  <td colspan="8" class="text-center text-muted py-4">
                    {{ __('users.table.empty') }}
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        @if ($users instanceof \Illuminate\Contracts\Pagination\Paginator)
          <div class="card-footer">
            {{ $users->links() }}
          </div>
        @endif
      </div>
    </div>
  </div>
@endsection
