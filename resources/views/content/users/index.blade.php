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

    {{-- //messages --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

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
                                placeholder="{{ __('users.filters.search_placeholder') }}"
                                value="{{ $filters['search'] ?? '' }}">
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
                                <th>{{ __('users.table.profile_picture') }}</th>
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

                                @endphp

                                <tr>
                                    {{-- Profile Picture --}}
                                    <td>
                                        <img src="{{ $user->getFirstMediaUrl('profile_image') ?: asset('images/default-avatar.png') }}"
                                            alt="Profile Picture" class="rounded-circle" width="40" height="40">
                                    </td>

                                    {{-- Name (من users.name كـ JSON قابل للترجمة) --}}
                                    <td>
                                        {{ is_array($user->name) ? $user->name['ar'] ?? ($user->name['en'] ?? '') : $user->name }}
                                    </td>

                                    {{-- Phone من Admin/Customer --}}
                                    <td>{{ $phone ?? '-' }}</td>


                                    {{-- Status --}}
                                    <td>
                                        @if ($user->is_active)
                                            <span
                                                class="badge bg-label-success">{{ __('users.status_badge.active') }}</span>
                                        @else
                                            <span
                                                class="badge bg-label-danger">{{ __('users.status_badge.inactive') }}</span>
                                        @endif
                                    </td>

                                    {{-- Created at --}}
                                    <td>{{ $user->created_at?->format('Y-m-d') }}</td>

                                    {{-- Actions --}}
                                    <td class="text-center">
                                        <div class="dropdown">
                                            <button
                                                class="btn btn-sm btn-icon btn-text-secondary rounded-pill dropdown-toggle hide-arrow"
                                                type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bx bx-dots-vertical-rounded"></i>
                                            </button>

                                            <div class="dropdown-menu dropdown-menu-end">
                                                @can('addresses.view')
                                                    <button type="button" class="dropdown-item" data-bs-toggle="modal"
                                                        data-bs-target="#addressesModal"
                                                        onclick="showUserAddresses({{ $user->id }}, '{{ is_array($user->name) ? $user->name['ar'] ?? ($user->name['en'] ?? '') : $user->name }}')">
                                                        <i class="bx bx-map me-2"></i>
                                                        {{ __('users.actions.view_addresses') }}
                                                    </button>
                                                @endcan


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
                                    <td colspan="6" class="text-center text-muted py-4">
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

{{-- Addresses Modal --}}
<div class="modal fade" id="addressesModal" tabindex="-1" aria-labelledby="addressesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addressesModalLabel">{{ __('users.addresses.title') }} - <span
                        id="userName"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="addressesContent">
                    <div class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">{{ __('common.loading') }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"
                    data-bs-dismiss="modal">{{ __('common.close') }}</button>
            </div>
        </div>
    </div>
</div>

<script>
    function showUserAddresses(userId, userName) {
        document.getElementById('userName').textContent = userName;
        document.getElementById('addressesContent').innerHTML = `
    <div class="text-center">
      <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">{{ __('common.loading') }}</span>
      </div>
    </div>
  `;

        fetch(`/admin/users/${userId}/addresses`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.error) {
                    throw new Error(data.error);
                }

                let html = '';

                if (data.addresses && data.addresses.length > 0) {
                    html = '<div class="row">';
                    data.addresses.forEach(address => {
                        html += `
            <div class="col-md-6 mb-3">
              <div class="card h-100">
                <div class="card-body">
                  <h6 class="card-title">
                    ${address.city ? address.city.name : '{{ __('users.addresses.unknown_city') }}'}
                    ${address.is_default ? '<span class="badge bg-primary ms-2">{{ __('users.addresses.default') }}</span>' : ''}
                  </h6>
                  <p class="card-text mb-2">
                    <strong>{{ __('users.addresses.street') }}:</strong> ${address.street || '{{ __('common.not_specified') }}'}
                  </p>
                  <p class="card-text mb-2">
                    <strong>{{ __('users.addresses.details') }}:</strong> ${address.details || '{{ __('common.not_specified') }}'}
                  </p>
                  ${address.latitude && address.longitude ? `
                    <p class="card-text mb-0">
                      <strong>{{ __('users.addresses.coordinates') }}:</strong>
                      ${address.latitude}, ${address.longitude}
                    </p>
                  ` : ''}
                </div>
              </div>
            </div>
          `;
                    });
                    html += '</div>';
                } else {
                    html = `
          <div class="text-center text-muted py-4">
            <i class="bx bx-map display-4 mb-3"></i>
            <p>{{ __('users.addresses.no_addresses') }}</p>
          </div>
        `;
                }

                document.getElementById('addressesContent').innerHTML = html;
            })
            .catch(error => {
                console.error('Error loading addresses:', error);
                document.getElementById('addressesContent').innerHTML = `
        <div class="alert alert-danger">
          <i class="bx bx-error-circle me-2"></i>
          {{ __('users.addresses.error_loading') }}: ${error.message}
        </div>
      `;
            });
    }
</script>
